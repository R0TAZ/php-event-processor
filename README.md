# PHP Event Processor

O **rotaz\event-processor** é um pacote para PHP pensado para processar eventos disparados por aplicações ou integrações, seguindo padrões modernos de arquitetura, inspirado pelo [spatie/laravel-webhook-client](https://github.com/spatie/laravel-webhook-client), mas com a proposta de ser desacoplado, agnóstico de framework e flexível para atender diferentes cenários empresariais.

---

## Conceito

O Event Processor centraliza o recebimento, validação, roteamento e processamento de eventos, permitindo que sistemas se comuniquem de forma desacoplada e eficiente. Cada evento é uma mensagem autônoma contendo contexto suficiente para ser processada sem depender do emissor.

---

## Padrões Adotados

- **Desacoplamento:** O processamento de eventos é feito sem dependência direta do emissor ou consumidor, seguindo princípios de Event-Driven Architecture.
- **Open/Closed Principle:** Fácil extensão para novos tipos de eventos sem alterar o núcleo do pacote.
- **Single Responsibility Principle:** Cada classe/processador é responsável apenas por um tipo de evento/ação.
- **Strategy Pattern:** Processadores de eventos podem ser registrados dinamicamente, permitindo múltiplas estratégias para diferentes eventos.
- **Middleware-like Pipeline:** Possibilidade de encadear validações, autenticações ou transformações antes do processamento final.

---

## Ganhos ao Utilizar

- **Manutenção Facilitada:** Novos eventos ou integrações podem ser adicionados sem modificar o código existente.
- **Baixo Acoplamento:** Possibilita reutilização de componentes e fácil integração com diferentes sistemas (PHP puro, Laravel, Symfony, etc).
- **Resiliência:** Processamento assíncrono (opcional) e possibilidade de retries/fallbacks.
- **Observabilidade:** Hooks para logging, métricas e rastreamento de eventos.
- **Testabilidade:** Processadores são facilmente testáveis de forma isolada.

---

## INSTALAÇÃO

```bash
composer require rotaz/event-processor
```
## CONFIGURAÇÃO BÁSICA

```php
php artisan vendor:publish --provider="Rotaz\EventProcessor\R0TAZEventProcessorServiceProvider" --tag="event-processor-config"    
```

```

'configs' => [
        [
            'name' => 'evt-incoming-message',
            'signing_secret' => env('INBOUND_EVENT_SIGNING_SECRET', 'your-signing-secret'),
            'signature_header_name' => 'Signature',
            'signature_validator' => \App\Integration\Kommo\SignatureValidator::class,
            'inbound_profile' => \App\Integration\Kommo\IncomingMessageProfile::class,
            'inbound_response' => \Rotaz\EventProcessor\Services\Messages\DefaultInboundResponse::class,
            'inbound_data_model' => \Rotaz\EventProcessor\Domains\Models\AbstractInboundData::class,
            'store_headers' => [],
            'process_inbound_data_job' => '\App\Integration\Kommo\IncomingMessageProcessor',
        ],
        [
            'name' => 'evt-kommo-contacts',
            'signing_secret' => env('INBOUND_EVENT_SIGNING_SECRET', 'your-signing-secret'),
            'signature_header_name' => 'Signature',
            'signature_validator' => \App\Integration\Kommo\SignatureValidator::class,
            'inbound_profile' => \App\Integration\Kommo\IncomingMessageProfile::class,
            'inbound_response' => \Rotaz\EventProcessor\Services\Messages\DefaultInboundResponse::class,
            'inbound_data_model' => \Rotaz\EventProcessor\Domains\Models\AbstractInboundData::class,
            'store_headers' => [],
            'process_inbound_data_job' => '\App\Integration\Kommo\ContactsProcessor',
        ],
    ],
  
 ```
    
## MIGRAÇÕES

```bash
php artisan vendor:publish --provider="Rotaz\EventProcessor\R0TAZEventProcessorServiceProvider" --tag="event-processor-migrations"
php artisan migrate
```


## USO BÁSICO

### Rota para receber eventos

Edit o arquivo de rotas (web.php ou api.php):

```php

use Illuminate\Support\Facades\Route;


Route::rotaz('v1/kommo/contacts', 'evt-kommo-contacts');
Route::rotaz('v1/kommo/incoming-message', 'evt-incoming-message');


```


```php


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Rotaz\EventProcessor\Traits\WithKommoPayloadHelper;

class IncomingMessageProcessor extends \Rotaz\EventProcessor\Services\Jobs\ProcessInboundDataJob
{
    use WithKommoPayloadHelper;
    
    public function handle(): void
    {
        Log::debug('IncomingMessageProcessor handle', ['inboundData' => $this->inboundData]);
        $data = $this->get_first_from($this->inboundData->payload , 'message.add');
        KommoFacade::check_incoming_message($data);
    }



}
```

---

## Solução Livre e Desacoplada

- **Sem dependências fixas:** Não depende de frameworks específicos.
- **Interface baseada em contratos:** Implemente seus próprios processadores conforme necessário.
- **Plugável:** Integre com filas, webhooks, APIs, CLI, etc.
- **Extensível:** Adicione middlewares, autenticação, validação ou logging conforme as necessidades do negócio.

---

## Problema Empresarial Resolvido

Empresas frequentemente precisam integrar múltiplos sistemas, processar notificações, webhooks ou atualizar estados baseados em eventos externos. O Event Processor abstrai e simplifica esse fluxo, reduzindo o acoplamento entre sistemas, facilitando a manutenção, escalabilidade e a governança de integrações.

---

## Futuras Extensões

- Suporte nativo a filas.
- Retry automático e dead letter queue.
- Ferramentas para debug e tracing.
- Adaptações para microservices e serverless.

---

## Referências

- [spatie/laravel-webhook-client](https://github.com/spatie/laravel-webhook-client)
- [Event-Driven Architecture (Martin Fowler)](https://martinfowler.com/articles/201701-event-driven.html)
- [Enterprise Integration Patterns](https://www.enterpriseintegrationpatterns.com/)

---

> Sinta-se livre para adaptar este pacote para sua realidade empresarial, mantendo o foco em flexibilidade, desacoplamento e boas práticas de arquitetura!
