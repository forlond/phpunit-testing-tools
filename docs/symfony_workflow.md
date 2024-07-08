# Symfony/Workflow

## Integration

- Use the `TestWorkflowTrait` to create workflow or workflow event instances.

## TestWorkflowTrait

### Workflow

```php
protected function createWorkflow(?callable $configure): TestWorkflow
```

Creates a `TestWorkflow` object. This instance extends the built-in `Workflow`.

The event dispatcher is a `TestEventDispatcher` in case it is necessary to assert event dispatcher logic.

The marking storage is a `TestMarkingStorage` that stores `Marking` instances in a internal data structure. However,
the marking storage can be set with a different `MarkingStoreInterface`.

The workflow can be configured by using the `$configure` closure.

The closure gets a `TestWorkflowBuilder` that can be used to define the workflow places, transitions, etc.

```php
$event = $this->createWorkflow(static function(TestWorkflowBuilder $builder) {
    $builder->name             = 'my_workflow';
    $builder->eventsToDispatch = [];
    $builder->markingStore     = new MethodMarkingStore(true, 'property');
    $builder
        ->definition
        ->setInitialPlaces('foo')
        ->addPlaces(['foo', 'bar'])
        ->addTransition($this->createTransition('forward', 'foo', 'bar'))
        ->addTransition($this->createTransition('backward', 'bar', 'foo'))
    ;
});
```

### Transition

```php
protected function createTransition(
    string       $name = 'transition',
    string|array $from = 'place1',
    string|array $to = 'place2',
): Transition
```

Helper method to create `Transition` objects.

### Events

Use one of the following methods to create a workflow event.

The events need a `Transition` object. Use `createTransition` to create a valid instance.

> [!NOTE]
> If the subscriber logic depends on the transition values, use the necessary transition name and/or places.

> [!IMPORTANT]
> The event method will create the necessary workflow based on the transition object.

Moreover, it is possible to configure the workflow by using the `$configure` closure. For example, if it is necessary
to change the workflow name or the initial place(s).

Some other events accept a context array.

```php
protected function createGuardEvent(
    object     $object,
    Transition $transition,
    ?callable  $configure = null,
): GuardEvent
```

---

```php
protected function createAnnounceEvent(
    object     $object,
    Transition $transition,
    array      $context = [],
    ?callable  $configure = null,
): AnnounceEvent
```

---

```php
protected function createCompletedEvent(
    object     $object,
    Transition $transition,
    array      $context = [],
    ?callable  $configure = null,
): CompletedEvent
```

---

```php
protected function createEnteredEvent(
    object     $object,
    Transition $transition,
    array      $context = [],
    ?callable  $configure = null,
): EnteredEvent
```

---

```php
protected function createEnterEvent(
    object     $object,
    Transition $transition,
    array      $context = [],
    ?callable  $configure = null,
): EnterEvent
```

---

```php
protected function createLeaveEvent(
    object     $object,
    Transition $transition,
    array      $context = [],
    ?callable  $configure = null,
): LeaveEvent
```

---

```php
protected function createTransitionEvent(
    object     $object,
    Transition $transition,
    array      $context = [],
    ?callable  $configure = null,
): TransitionEvent
```
