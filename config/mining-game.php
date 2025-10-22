<?php

return [

  'weights' => [
      // Ore ID => [Result => Weight]
      1 => [1 => 10, 2 => 5, 3 => 1],    // Copper Ore
      2 => [1 => 15, 2 => 4, 3 => 1],    // Tin Ore
      3 => [1 => 15, 2 => 4, 3 => 1],    // Iron Ore
      4 => [1 => 15, 2 => 4, 3 => 1],    // Silver Ore
      5 => [1 => 15, 2 => 4, 3 => 1],    // Gold Ore
  ],

  'experience' => [
      1 => 1,    // Copper Ore
      2 => 2,    // Tin Ore
      3 => 3,    // Iron Ore
      4 => 4,    // Silver Ore
      5 => 5,    // Gold Ore
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
