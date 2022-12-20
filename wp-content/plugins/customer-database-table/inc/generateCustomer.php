<?php
require_once plugin_dir_path(__FILE__) . 'generateOrders.php';

$customers = [
    1 => [
        'name' => 'Alice',
        'orders' => [
            '49.99',
            '75.32',
        ]
    ]
];

function generateCustomer(): array
{
    $adjectives = array("Amazing", "Astonishing", "Astounding", "Awesome", "Breathtaking", "Brilliant", "Classy", "Cool", "Dazzling", "Delightful", "Excellent", "Exceptional", "Exquisite", "Extraordinary", "Fabulous", "Fantastic", "Flawless", "Glorious", "Gnarly", "Good", "Great", "Groovy", "Groundbreaking", "Impeccable", "Impressive", "Incredible", "Laudable", "Legendary", "Lovely", "Luminous", "Magnificent", "Majestic", "Marvelous", "Neat", "Outstanding", "Perfect", "Phenomenal", "Polished", "Praiseworthy", "Premium", "Priceless", "Rad", "Remarkable", "Riveting", "Sensational", "Smashing", "Solid", "Spectacular", "Splendid", "Stellar", "Striking", "Stunning", "Stupendous", "Stylish", "Sublime", "Super", "Superb", "Supreme", "Sweet", "Swell", "Terrific", "Transcendent", "Tremendous", "Ultimate", "Wonderful", "Wondrous");
    $names = array("Abby", "Ace", "Allie", "Angel", "Annie", "Apollo", "Archie", "Athena", "Baby", "Bailey", "Bandit", "Baxter", "Bear", "Beau", "Bella", "Belle", "Benji", "Benny", "Bentley", "Blue", "Bo", "Bob", "Bonnie", "Boo", "Boomer", "Boots", "Brady", "Brandy", "Brody", "Bruno", "Brutus", "Bubba", "Buddy", "Buster", "Cali", "Callie", "Casey", "Cash", "Casper", "Champ", "Chance", "Charlie", "Chase", "Chester", "Chico", "Chloe", "Cleo", "Coco", "Cocoa", "Cody", "Cookie", "Cooper", "Copper", "Cuddles", "Daisy", "Dakota", "Dexter", "Diesel", "Dixie", "Duke", "Dusty", "Ella", "Ellie", "Elvis", "Emma", "Felix", "Finn", "Fluffy", "Frankie", "Garfield", "George", "Gigi", "Ginger", "Gizmo", "Grace", "Gracie", "Gus", "Hank", "Hannah", "Harley", "Hazel", "Heidi", "Henry", "Holly", "Honey", "Hunter", "Izzy", "Jack", "Jackson", "Jake", "Jasmine", "Jasper", "Jax", "Joey", "Josie", "Katie", "Kiki", "Kobe", "Kona", "Lacey", "Lady", "Layla", "Leo", "Lexi", "Lexie", "Lilly", "Lily", "Loki", "Lola", "Louie", "Lucky", "Lucy", "Luke", "Lulu", "Luna", "Mac", "Macy", "Maddie", "Madison", "Maggie", "Marley", "Max", "Maya", "Mia", "Mickey", "Midnight", "Millie", "Milo", "Mimi", "Minnie", "Miss kitty", "Missy", "Misty", "Mittens", "Mocha", "Molly", "Moose", "Muffin", "Murphy", "Nala", "Nikki", "Olive", "Oliver", "Ollie", "Oreo", "Oscar", "Otis", "Patches", "Peanut", "Pebbles", "Penny", "Pepper", "Phoebe", "Piper", "Precious", "Prince", "Princess", "Pumpkin", "Rascal", "Rex", "Riley", "Rocco", "Rocky", "Romeo", "Roscoe", "Rosie", "Roxie", "Roxy", "Ruby", "Rudy", "Rufus", "Rusty", "Sadie", "Salem", "Sally", "Sam", "Samantha", "Sammy", "Samson", "Sandy", "Sasha", "Sassy", "Scooter", "Scout", "Shadow", "Sheba", "Shelby", "Sierra", "Simba", "Simon", "Smokey", "Snickers", "Snowball", "Snuggles", "Socks", "Sophie", "Sparky", "Spike", "Spooky", "Stella", "Sugar", "Sydney", "Tank", "Teddy", "Thor", "Tiger", "Tigger", "Tinkerbell", "Toby", "Trixie", "Trouble", "Tucker", "Tyson", "Walter", "Willow", "Winnie", "Winston", "Zeus", "Ziggy", "Zoe", "Zoey");
    $suffix = array("Senior", "Junior", "The Third", "The Fourth", "The Fifth", "The Sixth", "The Seventh", "The Eighth", "The Ninth");

    $combined_name = $adjectives[array_rand($adjectives, 1)] . " " . $names[array_rand($names, 1)] . " " . $suffix[array_rand($suffix, 1)];
    return array(
        'name' => trim($combined_name)
    );
}