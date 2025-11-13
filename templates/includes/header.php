<header>
    <div class="container">
        <div class="logo">
            <i class="fas fa-feather-alt"></i> MonBlog
        </div>

        <nav>
            <?php \Core\Render\Fragment::nav([
                'user_connected' => $user_connected ?? false,
                'user_role' => $user_role ?? null
            ]); ?>
        </nav>
    </div>
</header>
