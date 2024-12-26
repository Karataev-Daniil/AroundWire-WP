<?php 
function custom_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'custom_excerpt_more');

function add_dynamic_classes_to_blog_content($content) {
    // Define default classes for tags outside of a table
    $generalClassMap = [
        'h2'     => 'title-large',
        'h3'     => 'title-medium',
        'h4'     => 'title-medium',
        'h5'     => 'title-medium',
        'p'      => 'body-medium-regular',
        'ul'     => 'body-medium-regular',
        'ol'     => 'body-medium-regular',
        'a'      => 'body-medium-regular',
        'b'      => 'body-medium-semibold',
        'strong' => 'body-medium-semibold',
    ];

    // Define specific classes for tags inside a table
    $tableClassMap = [
        'b'      => 'body-small-semibold',
        'strong' => 'body-small-semibold',
        'td'     => 'body-small-regular',
        'th'     => 'body-small-semibold',
        'tr'     => 'table-row',
    ];

    // Function to add a class to a tag
    $addClassToTag = function ($tag, $attributes, $class) {
        if (preg_match('/\bclass\s*=\s*["\']([^"\']*)["\']/', $attributes, $matches)) {
            // If the class attribute already exists
            $existingClasses = explode(' ', $matches[1]);
            if (!in_array($class, $existingClasses)) {
                $existingClasses[] = $class; // Add the new class if it's not already present
            }
            $updatedClass = 'class="' . implode(' ', $existingClasses) . '"';
            return preg_replace('/\bclass\s*=\s*["\']([^"\']*)["\']/', $updatedClass, $attributes);
        } else {
            // If the class attribute is missing, add it
            return $attributes . ' class="' . $class . '"';
        }
    };

    // Process content: match tags and add classes
    $content = preg_replace_callback('/<(\/?)(\w+)([^>]*)>/', function ($matches) use ($generalClassMap, $tableClassMap, $addClassToTag) {
        $closingTag = $matches[1]; // Closing tag or empty
        $tag = strtolower($matches[2]); // Tag name
        $attributes = $matches[3]; // Tag attributes

        if ($closingTag) {
            // Skip closing tags
            return $matches[0];
        }

        // Check if the tag is inside a table
        $isInTable = preg_match('/<table[^>]*>.*<' . $tag . '[^>]*>/is', $matches[0]);

        // Choose the appropriate class map
        $classMap = $isInTable ? $tableClassMap : $generalClassMap;

        // Add a class if defined for this tag
        if (isset($classMap[$tag])) {
            $attributes = $addClassToTag($tag, $attributes, $classMap[$tag]);
        }

        // Return the updated tag
        return '<' . $tag . $attributes . '>';
    }, $content);

    return $content;
}

// Hook the function into the WordPress content filter
add_filter('the_content', 'add_dynamic_classes_to_blog_content');

function clean_headings_in_content($content) {
    if (is_single()) {
        // Load the content into DOMDocument for processing
        $dom = new DOMDocument();
        
        // Suppress errors due to malformed HTML
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        // Clean headings (h2, h3, h4, h5) inside the content
        foreach (['h2', 'h3', 'h4', 'h5'] as $tag) {
            $headings = $dom->getElementsByTagName($tag);
            foreach ($headings as $heading) {
                // Trim the text content of each heading
                $heading->nodeValue = trim($heading->nodeValue);
            }
        }

        // Save the cleaned HTML content
        $cleaned_content = $dom->saveHTML();
        
        // Remove the XML declaration added by DOMDocument
        $cleaned_content = preg_replace('/<\?xml.*?>/', '', $cleaned_content);

        return $cleaned_content;
    }
    return $content;
}

add_filter('the_content', 'clean_headings_in_content');
?>