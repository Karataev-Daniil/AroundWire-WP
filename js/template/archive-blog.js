// testmes
document.addEventListener('DOMContentLoaded', function() {
    const copyButton = document.getElementById('copyButton');
    const copyMessage = document.getElementById('copyMessage');

    if (copyButton && copyMessage) {
        const permalink = copyButton.getAttribute('data-link');

        copyButton.addEventListener('click', function() {
            const tempInput = document.createElement('input');
            document.body.appendChild(tempInput);
            tempInput.value = permalink;
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);

            copyMessage.style.display = 'inline';
            setTimeout(() => {
                copyMessage.style.display = 'none';
            }, 2000);
        });
    }

    window.initTagContainers = function() {
        const tagContainers = document.querySelectorAll('.post-tags');
    
        tagContainers.forEach(function(container) {
            const tags = container.querySelectorAll('.tag-link');
            const showMoreButton = container.querySelector('.show-more');
    
            if (tags.length > 2) {
                tags.forEach((tag, index) => {
                    if (index > 1) {
                        tag.style.display = 'none';
                    }
                });
    
                if (showMoreButton) {
                    showMoreButton.style.display = 'inline';
    
                    showMoreButton.addEventListener('click', function() {
                        tags.forEach(tag => {
                            tag.style.display = 'inline';
                        });
                        this.style.display = 'none';
                    });
                }
            }
        });
    };

    window.initTagContainers();

    
    jQuery(document).ready(function($) {
        let currentTagId = null;
        let currentPage = 1;
        let resizeTimer;
        
        if ($('.blog__block-post').length) {
            showPostsSequentially(); // Animate posts
        }
        
        if ($('.tag-filter').length) {
            initTagContainers(); // Initialize tag containers
        }
        
        filterPosts(currentTagId || 'all', currentPage); // Filter posts
        
        // Handle window resize
        $(window).resize(function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                filterPosts(currentTagId || 'all', currentPage);
            }, 300);
        });
        
        // Tag filter click
        $('.tag-filter').on('click', function() {
            $('.tag-filter').removeClass('active');
            $(this).addClass('active');
            
            currentTagId = $(this).data('term-id');
            currentPage = 1;
            
            filterPosts(currentTagId, currentPage);
        });
        
        // Next page click
        $('#next-page').on('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                filterPosts(currentTagId || 'all', currentPage);
            }
        });
        
        // Previous page click
        $('#prev-page').on('click', function() {
            if (currentPage > 1) { 
                currentPage--;
                filterPosts(currentTagId || 'all', currentPage);
            }
        });
        
        // Filter posts function
        function filterPosts(tagId, page) {
            $.ajax({
                url: myData.ajaxurl,
                type: 'POST',
                data: {
                    action: 'filter_posts',
                    tag_id: tagId || 'all',
                    page: page,
                    screen_width: $(window).width()
                },
                success: function(response) {
                    const data = JSON.parse(response);
                    $('#posts-container').html(data.posts_html);
                    
                    if ($('.blog__block-post').length) {
                        showPostsSequentially(); // Re-apply post animation
                    }
                    
                    if ($('.tag-filter').length) {
                        initTagContainers(); // Re-initialize tags
                    }
                    
                    updatePagination(data); // Update pagination controls
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + error);
                }
            });
        }
        
        function updatePagination(response) {
            const hasMorePosts = response.has_more_posts;
            totalPages = response.total_pages; 
            currentPage = response.current_page;
            
            let pageLimit;
            if (window.innerWidth <= 701) {
                pageLimit = 4; 
            } else {
                pageLimit = 9; 
            }
            
            if (totalPages > 1) {
                $('#pagination-controls').show();
                $('#prev-page').prop('disabled', currentPage === 1);
                $('#next-page').prop('disabled', !hasMorePosts);
                $('#page-numbers').html('');
                
                const visiblePages = Math.min(pageLimit, totalPages);
                let startPage, endPage;
                
                if (totalPages <= visiblePages) {
                    startPage = 1;
                    endPage = totalPages;
                } else {
                    startPage = Math.max(1, currentPage - Math.floor(pageLimit - 2));
                    endPage = Math.min(totalPages, startPage + visiblePages - 1);
                    
                    if (endPage - startPage < visiblePages - 1) {
                        startPage = Math.max(1, endPage - visiblePages + 1);
                    }
                }
                
                for (let i = startPage; i <= endPage; i++) {
                    appendPageButton(i, currentPage);
                }
                
                if (endPage < totalPages) {
                    $('#page-numbers').append('<button class="ellipsis button-small">...</button>');
                    appendPageButton(totalPages, currentPage);
                }
                
                if ($('#pagination-controls').length) {
                    showPaginationSequentially(); // Animate pagination if present
                }
            } else {
                $('#pagination-controls').hide();
            }
        }
        
        function updateButtonLabels() {
            const prevButton = document.getElementById('prev-page');
            const nextButton = document.getElementById('next-page');
        
            if (prevButton && nextButton) {
                if (window.innerWidth <= 550) {
                    prevButton.textContent = '<'; 
                    nextButton.textContent = '>'; 
                } else {
                    prevButton.textContent = '< Prev'; 
                    nextButton.textContent = 'Next >'; 
                }
            }
        }
        
        window.addEventListener('load', updateButtonLabels);
        window.addEventListener('resize', updateButtonLabels);
        
        // Helper function to create and append page buttons
        function appendPageButton(page, currentPage) {
            let pageButton = $('<button class="page-number button-small">' + page + '</button>');
            if (page === currentPage) {
                pageButton.addClass('active');
            }
            pageButton.on('click', function() {
                currentPage = page;
                filterPosts(currentTagId || 'all', currentPage);
            });
            $('#page-numbers').append(pageButton);
        }
    
        // Show posts with delay
        function showPostsSequentially() {
            $('.blog__block-post').each(function(index) {
                var post = $(this);
                var img = post.find('img');
    
                if (img.length > 0) {
                    img.on('load', function() {
                        setTimeout(function() {
                            post.addClass('visible');
                        }, 25 * index);
                    }).each(function() {
                        if (this.complete) $(this).load();
                    });
                } else {
                    setTimeout(function() {
                        post.addClass('visible');
                    }, 25 * index);
                }
            });
        }
    
        // Function to animate pagination buttons
        function showPaginationSequentially() {
            const paginationElements = $('#pagination-controls').add('#pagination-controls'); // Include buttons and page numbers
        
            paginationElements.each(function(index) {
                var element = $(this);
                setTimeout(function() {
                    element.addClass('visible'); // Add visible class with delay
                }, 1000 * index); // Delay based on index
            });
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Adding alt text to images
    function addAltTextToImages() {
        const postTitleElement = document.querySelector('.blog-post__title h1');
        const postContent = document.querySelector('.blog-post-content');
        if (!postTitleElement || !postContent) return;

        const postTitle = postTitleElement.textContent.trim();
        const images = postContent.querySelectorAll('p > img');

        images.forEach((img, index) => {
            if (!img.alt.trim()) {
                img.alt = `${postTitle} ${index + 1}`;
            }
        });
    }

    // Opening links in a new tab
    function updateLinksToOpenInNewTab() {
        document.querySelectorAll('.blog-post-content a').forEach(link => {
            link.target = '_blank';
        });
    }

    // Replacing link buttons with actual buttons
    function replaceLinksWithButtons() {
        document.querySelectorAll('.search-button').forEach((link, index) => {
            const button = document.createElement('button');
            button.className = link.className;
            button.id = `searchButton_${index}`;
            button.innerHTML = link.innerHTML;
            link.replaceWith(button);
        });
    }

    // Managing the search panel
    function initSearchPanel() {
        const searchField = document.querySelector('.search-field');
        const searchPanel = document.getElementById('searchPanel');
        const clearButton = document.getElementById('clear-search');
        const searchForm = document.querySelector('.search-form');
    
        if (!searchPanel || !searchField || !searchForm) return;
    
        let isPanelOpen = false;

        // Function to open the search panel and focus the search field
        function openSearchPanel() {
            searchPanel.style.marginTop = '0px';
            setTimeout(() => searchField.focus(), 50);
            isPanelOpen = true;
        }

        // Function to close the search panel
        function closeSearchPanel() {
            searchPanel.style.marginTop = '-80px';
            searchField.blur();
            isPanelOpen = false;
        }

        // Function to open or close the search panel
        function toggleSearchPanel() {
            if (isPanelOpen) {
                closeSearchPanel();
            } else {
                openSearchPanel(); 
            }
        }
    
        // Add click event listener to search buttons
        document.querySelectorAll('[id^="searchButton_"]').forEach(button => {
            button.addEventListener('click', toggleSearchPanel);
        });
    
        // Automatically open the search panel if the URL contains a search parameter
        if (window.location.search.includes('s=')) {
            openSearchPanel();
        }
    
        // Update the panel state (toggle clear button visibility)
        function updateSearchPanel() {
            const hasContent = searchField.value.trim() !== '';
            searchPanel.classList.toggle('has-content', hasContent);
            clearButton.style.display = hasContent ? 'flex' : 'none';
        }
    
        // Update the panel state when the search field value changes
        searchField.addEventListener('input', updateSearchPanel);
    
        // Clear the search field and update the panel state
        clearButton?.addEventListener('click', () => {
            searchField.value = '';
            updateSearchPanel();
            searchField.focus();
        });
    
        // Validate search input before submitting the form
        searchForm.addEventListener('submit', event => {
            if (!searchField.value.trim()) {
                event.preventDefault();
                alert('Please enter a search query before submitting.');
            }
        });
    
        // Initialize the panel state on load
        updateSearchPanel();
    }    

    // Creating a table of contents with navigation
    function initContentNavigation() {
        const contentList = document.getElementById("content-list");
        const headings = document.querySelectorAll(".blog-post-content h2, .blog-post-content h3, .blog-post-content h4, .blog-post-content h5, .blog-post-content h6");
        const contentNavigation = document.querySelector(".content-navigation");
    
        if (headings.length > 0) {
            const overlay = document.createElement("div");
            overlay.classList.add("highlight-overlay");
            document.body.appendChild(overlay);
        
            let lastLevels = {};
            let currentParent = contentList;
        
            headings.forEach((heading, index) => {
                if (!heading.id) {
                    heading.id = `heading-${index}`;
                }

                const level = parseInt(heading.tagName[1]); // Get the number from tag name (e.g., 2 from h2)

                const link = document.createElement("a");
                link.href = `#${heading.id}`;
                link.textContent = heading.textContent;
                link.classList.add("content-link", "link-small-underline");

                const listItem = document.createElement("li");
                listItem.classList.add(`level-${level}`);
                listItem.appendChild(link);

                if (!lastLevels[level]) {
                    lastLevels[level] = listItem;
                }

                // Determine the parent based on hierarchy
                if (level === 2) {
                    contentList.appendChild(listItem); // h2 is always a top-level item
                    currentParent = listItem;
                } else {
                    // Check if there's a parent list for this level
                    const parentLevel = level - 1;
                    if (lastLevels[parentLevel]) {
                        let parentList = lastLevels[parentLevel].querySelector("ul");
                        if (!parentList) {
                            parentList = document.createElement("ul");
                            lastLevels[parentLevel].appendChild(parentList);
                        }
                        parentList.appendChild(listItem);
                    } else {
                        contentList.appendChild(listItem); // Fallback for incorrect hierarchy
                    }
                }

                // Update the last seen level
                lastLevels[level] = listItem;
            });

            contentList.addEventListener("click", function (event) {
                if (event.target.classList.contains("content-link")) {
                    event.preventDefault();
                    const targetId = event.target.getAttribute("href").substring(1);
                    const targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        const offset = window.innerHeight * 0.02;
                        const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY - offset;

                        highlightArea(targetElement);

                        window.scrollTo({ top: targetPosition, behavior: "smooth" });

                        targetElement.classList.add("highlighted");

                        setTimeout(() => {
                            targetElement.classList.add("removed");
                            setTimeout(() => {
                                targetElement.classList.remove("highlighted", "removed");
                            }, 300);
                        }, 1000);
                    }
                }
            });

            function highlightArea(element) {
                const rect = element.getBoundingClientRect();
                overlay.style.top = `${rect.top + window.scrollY}px`;
                overlay.style.left = `${rect.left}px`;
                overlay.style.width = `${rect.width}px`;
                overlay.style.height = `${rect.height}px`;
                overlay.style.display = 'block';
            }

            if (contentNavigation) {
                contentNavigation.style.display = 'block';
            }
        } else {
            if (contentNavigation) {
                contentNavigation.style.display = 'none';
            }
        }
    }

    
    // Calling all functions
    addAltTextToImages();
    updateLinksToOpenInNewTab();
    replaceLinksWithButtons();
    initSearchPanel();
    initContentNavigation();
});