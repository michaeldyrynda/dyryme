<?php

Event::listen('link.creating', 'Dyryme\Validators\LinkValidator@fire');
