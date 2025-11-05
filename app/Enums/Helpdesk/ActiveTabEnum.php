<?php

namespace App\Enums\Helpdesk;

enum ActiveTabEnum: string {
    case OPEN = 'open';
    case CLOSED = 'closed';
    case ASSIGNED = 'assigned';
    case MY = 'my';
}
