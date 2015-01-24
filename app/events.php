<?php

Event::listen('link.creating', 'Dyryme\Validators\LinkValidator@fire');
Event::listen('user.creating', 'Dyryme\Validators\RegistrationValidator@fire');
Event::listen('link.forceDeleting', 'Dyryme\Listeners\LinkForceDeletingListener');
