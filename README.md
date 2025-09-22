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

## Exemplo de Uso

```php
// Definindo um processador para um evento do tipo "UserRegistered"
class UserRegisteredProcessor implements EventProcessorInterface
{
    public function canProcess(Event $event): bool
    {
        return $event->type() === 'user.registered';
    }

    public function process(Event $event): void
    {
        // lógica para processar o evento
    }
}

// Registrando processadores
$dispatcher = new EventDispatcher();
$dispatcher->registerProcessor(new UserRegisteredProcessor());

// Recebendo e processando um evento
$event = Event::fromArray($_POST); // ou de uma fila, webhook, etc
$dispatcher->dispatch($event);
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
