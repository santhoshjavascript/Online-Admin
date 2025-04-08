<?php

namespace App\Enums;

enum Status: string
{
    case Draft = 'Draft';
    case Published = 'Published';
    case Archived = 'Archived';
}