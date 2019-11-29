<!doctype html>
<title>Example</title>
<style>
    .cards {
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
    }
    .card {
        padding: 0;
        flex: 0 0 200px;
        margin: 10px;
        border: 1px solid #ccc;
        box-shadow: 2px 2px 6px 0px  rgba(0,0,0,0.3);
    }
    .card img {
        max-width: 100%;
    }
    .card .text {
        padding: 0 20px 20px;
    }
    .card .text > a {
        background: gray;
        border: 0;
        color: white;
        padding: 10px;
        display: inherit;
        text-decoration: none;
        text-align: center;
    }
</style>

<main class="cards">
    <article class="card">
        <div class="text">
            <h3><?php _e( 'Report Issue.'); ?></h3>
            <p><?php _e( 'Help us improving the plugin to make awesome for all like you by reporting any issue if found.', 'fael' ); ?></p>
            <a href="https://github.com/cybercraftit/user-frontend-for-elementor/issues/new" target="_blank"><?php _e( 'Report issue', 'fael' ); ?></a>
        </div>
    </article>
    <article class="card">
        <div class="text">
            <h3><?php _e( 'Rate Us.', 'fael' ); ?></h3>
            <p><?php _e( 'If you find this plugin useful, let it be reliable to others as well by rating us.', 'fael' ); ?></p>
            <a href="https://wordpress.org/support/plugin/user-frontend-for-elementor/reviews/#new-post" target="_blank"><?php _e( 'Rate', 'fael' ); ?></a>
        </div>
    </article>
    <article class="card">
        <div class="text">
            <h3><?php _e( 'Feature Request.', 'fael' ); ?></h3>
            <p><?php _e( 'Got something great to suggest about this plugin ? Please, feel free to let us know.', 'fael'); ?></p>
            <a href="https://cybercraftit.com/" target="_blank"><?php _e( 'Contact us', 'fael'); ?></a>
        </div>
    </article>
</main>