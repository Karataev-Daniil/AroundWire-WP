jQuery(document).ready(function($) {
    // Show posts sequentially on page load
    $('.blog__block-post').each(function(index) {
        var post = $(this);
        var img = post.find('img'); // Assuming there is an <img> inside each post

        // If there is an image, wait for it to load
        if (img.length > 0) {
            img.on('load', function() {
                setTimeout(function() {
                    post.addClass('visible');
                }, 100 * index);
            }).each(function() {
                // Handle the case where the image is already loaded
                if (this.complete) $(this).load();
            });
        } else {
            // If no image, show the post immediately
            setTimeout(function() {
                post.addClass('visible');
            }, 100 * index);
        }
    });

    $('#load-more').on('click', function() {
        var button = $(this),
            page = button.data('page'),
            postCount = $('#custom-posts-container .blog__block-post').length,
            postsPerPage = 6,
            data = {
                'action': 'load_more_posts',
                'page': page,
                'posts_per_page': postsPerPage,
                'post_count': postCount
            };
    
        $.ajax({
            url: load_more_params.ajaxurl,
            data: data,
            type: 'POST',
            beforeSend: function(xhr) {
                button.text('loading...');
            },
            success: function(response) {
                if (response.trim()) {
                    var newPosts = $(response).hide();
                    $('#custom-posts-container').append(newPosts);
    
                    // Show posts sequentially after image loading
                    $('#custom-posts-container .blog__block-post:hidden').each(function(index) {
                        var post = $(this);
                        var img = post.find('img');
    
                        if (img.length > 0) {
                            img.on('load', function() {
                                setTimeout(function() {
                                    post.fadeIn(500).addClass('visible');
                                }, 100 * index);
                            }).each(function() {
                                if (this.complete) $(this).load();
                            });
                        } else {
                            setTimeout(function() {
                                post.fadeIn(500).addClass('visible');
                            }, 100 * index);
                        }
                    });

                    if (typeof initTagContainers === 'function') {
                        initTagContainers();
                    }
    
                    var loadedPostsCount = newPosts.filter('.blog__block-post').length;
    
                    if (loadedPostsCount < postsPerPage) {
                        button.remove();
                    } else {
                        button.text('Load More Articles').data('page', page + 1);
                    }
                } else {
                    button.remove();
                }
            }
        });
    });
});
