<?php
// $meta_title = "Family Privacy Protection Services in USA | Remove Kids' Info Online";
// $meta_description = "Protect your family’s privacy online with PrivacyDuck. We specialize in removing kids’ personal information from the internet. Trusted family data privacy services in the USA.";
// $meta_url = "https://privacyduck.com/family";
// $meta_keywords = "family privacy protection service, remove kids personal info online, family personal data removal from internet, family privacy services in USA";
// include_once(BASEPATH . "/src/common/meta.php");
main_head_start();
?>
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .post-card {
        transition: all 0.3s ease;
    }

    .post-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .prose {
        max-width: none;
    }

    .prose h2 {
        font-size: 1.875rem;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #1f2937;
    }

    .prose h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        color: #374151;
    }

    .prose p {
        margin-bottom: 1.25rem;
        line-height: 1.75;
        color: #4b5563;
    }

    .prose ul {
        margin-bottom: 1.25rem;
        padding-left: 1.5rem;
    }

    .prose li {
        margin-bottom: 0.5rem;
        color: #4b5563;
    }

    .prose blockquote {
        border-left: 4px solid #10b981;
        padding-left: 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        color: #6b7280;
        background: #f9fafb;
        padding: 1.5rem;
        border-radius: 0.5rem;
    }

    .prose code {
        background: #f3f4f6;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        color: #dc2626;
    }

    .prose pre {
        background: #1f2937;
        color: #f9fafb;
        padding: 1.5rem;
        border-radius: 0.5rem;
        overflow-x: auto;
        margin: 1.5rem 0;
    }

    .prose pre code {
        background: none;
        color: inherit;
        padding: 0;
    }
</style>
<?php
main_head_end();
main_header("black");
// main_splash();
?>
<div class="bg-[#FAFAFA]">
<?php require_once(BASEPATH . "/src/pages/Blog/detail/breadcrumb.php"); ?>
<?php require_once(BASEPATH . "/src/pages/Blog/detail/article.php"); ?>
<?php require_once(BASEPATH . "/src/pages/Blog/detail/related_article.php"); ?>
<?php require_once(BASEPATH . "/src/pages/Blog/subscribe.php"); ?>
</div>
<?php main_footer(); ?>

<script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Animate elements on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all animated elements
        document.querySelectorAll('.animate-fade-in-up, .post-card').forEach(element => {
            if (!element.style.animationDelay) {
                element.style.opacity = '0';
                element.style.transform = 'translateY(30px)';
                element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(element);
            }
        });
    </script>