<?php

require_once plugin_dir_path(__FILE__) . '../utils/randomFloat.php';


function generateProduct(): array
{
    $adjectives = array("Amazing", "Astonishing", "Astounding", "Awesome", "Breathtaking", "Brilliant", "Classy", "Cool", "Dazzling", "Delightful", "Excellent", "Exceptional", "Exquisite", "Extraordinary", "Fabulous", "Fantastic", "Flawless", "Glorious", "Gnarly", "Good", "Great", "Groovy", "Groundbreaking", "Impeccable", "Impressive", "Incredible", "Laudable", "Legendary", "Lovely", "Luminous", "Magnificent", "Majestic", "Marvelous", "Neat", "Outstanding", "Perfect", "Phenomenal", "Polished", "Praiseworthy", "Premium", "Priceless", "Rad", "Remarkable", "Riveting", "Sensational", "Smashing", "Solid", "Spectacular", "Splendid", "Stellar", "Striking", "Stunning", "Stupendous", "Stylish", "Sublime", "Super", "Superb", "Supreme", "Sweet", "Swell", "Terrific", "Transcendent", "Tremendous", "Ultimate", "Wonderful", "Wondrous");
    $names = array("Knife", "Taco Kit", "Microwave", "Shovel", "Bottle Opener", "Water Bottle", "T-Shirt", "Bicycle", "Baby Doll", "Board Game", "Computer", "Toaster", "Washer", "Cup", "Shoe", "Slipper", "Coat Hanger", "Vase", "Phone", "Blocks", "Money Clip", "Mouse Pad", "Watch", "Phone", "Stocking", "Boot");

    $combined_name = $adjectives[array_rand($adjectives, 1)] . " " . $names[array_rand($names, 1)];
    $cost = randFloat(1, 100, 2);

    return [
        'cost' => $cost,
        'name' => trim($combined_name)
    ];
}