<?php

  return [

    'weights' => [
        // Ore ID => [Result => Weight]
        1 => [1 => 10, 2 => 5, 3 => 1],    // Copper Ore
        2 => [1 => 15, 2 => 4, 3 => 1],    // Tin Ore
    ],

    'experience' => [
        1 => 1,    // Copper Ore
        2 => 2,    // Tin Ore
    ],

    // Future use

    'tools' => [
        'pickaxe' => [
            'durability' => 100,
            'efficiency' => 1.25,
        ],
    ],

    'cooldowns' => [
        'default' => 10, // seconds
    ],

];

