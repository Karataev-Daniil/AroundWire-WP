<?php

$reviews = [
    [
        'img' => 'https://pro.aroundwire.com/wp-content/uploads/2024/08/John.webp', 
        'name' => 'John Garrison',
        'profession' => 'General Contractor',
        'text' => 'With so many sketchy platforms out there, I was initially skeptical about AroundWire. Signing up didn\'t cost anything, and now I have one more place to promote myself. Nice work.',
        'video' => 'https://www.youtube.com/embed/wqC-uwKB7I0'
    ],
    [
        'img' => 'https://pro.aroundwire.com/wp-content/uploads/2024/08/WIll.webp',
        'name' => 'Will Donovan',
        'profession' => 'Plumber',
        'text' => 'I know we\'re always cautious about new things, but AroundWire is worth trying. I listed my plumbing services and got some interest from new clients.',
        'video' => 'https://www.youtube.com/embed/dk_8eAcwxnY'
    ],
    [
        'img' => 'https://pro.aroundwire.com/wp-content/uploads/2024/08/Anthony-Perkins.webp',
        'name' => 'Anthony Perkins',
        'profession' => 'HVAC Technician',
        'text' => 'I listed my HVAC services. It\'s hot as hell in Vegas, and another source of customers only helps. Didn\'t cost me anything to list. Thanks for the promo.',
        'video' => 'https://www.youtube.com/embed/EU5up9nRUFA'
    ],
    [
        'img' => 'https://pro.aroundwire.com/wp-content/uploads/2024/08/Veronica-Smillers.webp',
        'name' => 'Veronica Smillers',
        'profession' => 'Cleaning Specialist',
        'text' => 'I hate doing cold calls. When I promote my cleaning business, it is always a PIA. Thanks, AW, for building a platform where I can just list my stuff and get customers. Thanks for supporting small business.',
        'video' => 'https://www.youtube.com/embed/qB9r0sRt2PI'
    ],
    [
        'img' => 'https://pro.aroundwire.com/wp-content/uploads/2024/08/Ana-Smith.webp',
        'name' => 'Ana Smith',
        'profession' => 'Landscape Designer',
        'text' => 'I\'m in landscaping design, and finding new customers has been a chore, to say the least. With AroundWire, I don\'t have to worry about that at all.',
        'video' => 'https://www.youtube.com/embed/qypL9E1gSpI'
    ],
    [
        'img' => 'https://pro.aroundwire.com/wp-content/uploads/2024/08/Tyler-Johnson.webp',
        'name' => 'Tyler Johnson',
        'profession' => 'Professional Locksmith',
        'text' => 'As a locksmith, I like to be solid, just like the keys I make. Listed my stuff. Hopefully, I get customers. Seems to be a nice platform.',
        'video' => 'https://www.youtube.com/embed/uUfXbPGflcY'
    ]
];
?>
<section class="buyer-stories__wrapper" style="background-color: <?php the_field('buyer-stories_color') ?>;">
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "AroundWire",
      "url": "https://pro.aroundwire.com/",
      "logo": "https://pro.aroundwire.com/wp-content/uploads/2024/07/logo_black.svg", 
      "sameAs": [
        "https://www.facebook.com/aroundwire",
        "https://www.instagram.com/aroundwire"
      ],
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "5",
        "reviewCount": "6"
      },
      "review": [
        <?php
        $reviews_ld = [];
        foreach ($reviews as $review) {
            $reviews_ld[] = [
                "@type" => "Review",
                "author" => [
                    "@type" => "Person",
                    "name" => $review['name']
                ],
                "reviewRating" => [
                    "@type" => "Rating",
                    "ratingValue" => "5", 
                    "bestRating" => "5",
                    "worstRating" => "1"
                ],
                "reviewBody" => $review['text'],
                "datePublished" => "2024-09-20" 
            ];
        }
    
        echo wp_json_encode($reviews_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        ?>
      ]
    }
    </script>
    <div class="container">
        <div class="item-reviews">
            <?php foreach ($reviews as $review) : ?>
                <div class="item-review">
                    <div class="block-icon" onclick="openVideoModal('<?php echo $review['video']; ?>')">
                        <img class="icon-reviews" src="<?php echo $review['img']; ?>" alt="<?php echo $review['name']; ?>" loading="lazy">
                        <img class="push-button" src="/wp-content/uploads/2024/08/Play-button.svg" alt="push button" loading="lazy">
                    </div>
                    <h3 class="title-larger"><?php echo $review['name']; ?></h3>
                    <div class="uppercase-small">I’m a <?php echo $review['profession']; ?></div>
                    <p class="body-medium-regular"><?php echo $review['text']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<div id="videoModal" class="video-modal">
    <span class="close" onclick="closeVideoModal()"></span>
    <div class="video-container">
        <iframe id="videoFrame" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin"></iframe>
    </div>
</div>