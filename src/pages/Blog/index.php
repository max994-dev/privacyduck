<?php
$meta_title = "Online Privacy Insights and Expert Guides Blog | PrivacyDuck";
$meta_description = "Stay updated on personal data protection, online privacy risks, and practical strategies to remove sensitive information from the web with PrivacyDuck insights.";
$meta_url = "https://privacyduck.com/blog";
// optional if your meta.php uses it:
$meta_keywords = "";
include_once(BASEPATH . "/src/common/meta.php");

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
</style>
<?php
main_head_end();
main_header();
// main_splash();
?>
<div class="bg-[#FAFAFA]">
    <?php require_once(BASEPATH . "/src/pages/Blog/intro.php"); ?>
    <?php require_once(BASEPATH . "/src/pages/Blog/content.php"); ?>
    <?php require_once(BASEPATH . "/src/pages/Blog/subscribe.php"); ?>
</div>
<?php main_footer(); ?>
<script>
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
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

    // Observe all post cards
    document.querySelectorAll('.post-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
</script>
