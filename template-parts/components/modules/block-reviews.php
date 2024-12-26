
<?php
$reviews = [
    [
        'img' => 'wp-content/uploads/2024/08/John.webp', 
        'name' => 'John Garrison',
        'profession' => 'General Contractor',
        'text' => 'With so many sketchy platforms out there, I was initially skeptical about AroundWire. Signing up didn\'t cost anything, and now I have one more place to promote myself. Nice work.',
        'video' => 'https://www.youtube.com/embed/wqC-uwKB7I0'
    ],
    [
        'img' => 'wp-content/uploads/2024/08/WIll.webp',
        'name' => 'Will Donovan',
        'profession' => 'Plumber',
        'text' => 'I know we\'re always cautious about new things, but AroundWire is worth trying. I listed my plumbing services and got some interest from new clients.',
        'video' => 'https://www.youtube.com/embed/dk_8eAcwxnY'
    ],
    [
        'img' => 'wp-content/uploads/2024/08/Anthony-Perkins.webp',
        'name' => 'Anthony Perkins',
        'profession' => 'HVAC Technician',
        'text' => 'I listed my HVAC services. It\'s hot as hell in Vegas, and another source of customers only helps. Didn\'t cost me anything to list. Thanks for the promo.',
        'video' => 'https://www.youtube.com/embed/EU5up9nRUFA'
    ],
    [
        'img' => 'wp-content/uploads/2024/08/Veronica-Smillers.webp',
        'name' => 'Veronica Smillers',
        'profession' => 'Cleaning Specialist',
        'text' => 'I hate doing cold calls. When I promote my cleaning business, it is always a PIA. Thanks, AW, for building a platform where I can just list my stuff and get customers. Thanks for supporting small business.',
        'video' => 'https://www.youtube.com/embed/qB9r0sRt2PI'
    ],
    [
        'img' => 'wp-content/uploads/2024/08/Ana-Smith.webp',
        'name' => 'Ana Smith',
        'profession' => 'Landscape Designer',
        'text' => 'I\'m in landscaping design, and finding new customers has been a chore, to say the least. With AroundWire, I don\'t have to worry about that at all.',
        'video' => 'https://www.youtube.com/embed/qypL9E1gSpI'
    ],
    [
        'img' => 'wp-content/uploads/2024/08/Tyler-Johnson.webp',
        'name' => 'Tyler Johnson',
        'profession' => 'Professional Locksmith',
        'text' => 'As a locksmith, I like to be solid, just like the keys I make. Listed my stuff. Hopefully, I get customers. Seems to be a nice platform.',
        'video' => 'https://www.youtube.com/embed/uUfXbPGflcY'
    ]
];
?>
<?php foreach ($reviews as $review): ?>
    <div class="item-reviews">
        <?php if ($args['type'] === 'variant1') : ?>
            <div class="block-icon" onclick="openVideoModal('<?php echo $review['video']; ?>')">
                <img class="icon-reviews" src="<?php echo $review['img']; ?>" alt="<?php echo $review['name']; ?>" loading="lazy">
                <img class="push-button" src="/wp-content/uploads/2024/08/Play-button.svg" alt="push button" loading="lazy">
            </div>
            <h3 class="title-larger"><?php echo $review['name']; ?></h3>
            <div class="uppercase-small">I’m a <?php echo $review['profession']; ?></div>
            <p class="body-medium-regular"><?php echo $review['text']; ?></p>
        <?php elseif ($args['type'] === 'variant2') : ?>
            <img src="<?php echo $review['img']; ?>" loading="lazy" alt="<?php echo $review['name']; ?>">
            <div>
                <h3><?php echo $review['name']; ?></h3>
                <p><?php echo $review['profession']; ?></p>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
