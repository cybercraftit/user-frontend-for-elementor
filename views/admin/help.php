<!doctype html>
<title>Example</title>
<style>
    .cards {
        margin-top: 30px;
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
        text-align: center;
        padding-bottom:20px;
    }
    .card img {
        max-width: 100%;
    }
    .card .text {
        padding: 0 20px 20px;
    }
    .card .text p{
        margin-bottom: 30px;
    }
    .card .text > a {
        background: #333333;
        border: 0;
        color: white;
        padding: 10px;
        text-decoration: none;
        text-align: center;
    }
    .card .text > a.help-link {
        background: #333333;
        border: 0;
        color: white;
        padding: 10px;
        display: inherit;
        text-decoration: none;
        text-align: center;
    }
    .card .text > a:hover {
        background: #EB245A;
    }
    .help-icon{
        margin-top:20px;
    }
    .help-icon i{
        font-size:30px;
    }
</style>

<main class="cards">
    <article class="card">
        <div class="text">
            <div class="help-icon"><i class="dashicons dashicons-welcome-write-blog"></i></div>
            <h3><?php _e( 'Documentation'); ?></h3>
            <p><?php _e( 'Stuck with something at any point using it. You can get help from our complete documentation here', 'fael' ); ?></p>
            <a href="http://docs.cybercraftit.com/docs/user-frontend-for-elementor/" target="_blank" class="help-link"><?php _e( 'Read Doc', 'fael' ); ?></a>
        </div>
    </article>
    <article class="card">
        <div class="text">
            <div class="help-icon"><i class="dashicons dashicons-info"></i></div>
            <h3><?php _e( 'Report Issue'); ?></h3>
            <p><?php _e( 'Help us improving the plugin to make awesome for all like you by reporting any issue if found.', 'fael' ); ?></p>
            <a href="https://github.com/cybercraftit/user-frontend-for-elementor/issues/new" target="_blank" class="help-link"><?php _e( 'Report issue', 'fael' ); ?></a>
        </div>
    </article>
    <article class="card">
        <div class="text">
            <div class="help-icon"><i class="dashicons dashicons-star-filled"></i></div>
            <h3><?php _e( 'Rate Us', 'fael' ); ?></h3>
            <p><?php _e( 'If you find this plugin useful, let it be reliable to others as well by rating us.', 'fael' ); ?></p>
            <a href="https://wordpress.org/support/plugin/user-frontend-for-elementor/reviews/#new-post" target="_blank" class="help-link"><?php _e( 'Rate', 'fael' ); ?></a>
        </div>
    </article>
    <article class="card">
        <div class="text">
            <div class="help-icon"><i class="dashicons dashicons-tag"></i></div>
            <h3><?php _e( 'Feature Request', 'fael' ); ?></h3>
            <p><?php _e( 'Got something great to suggest about this plugin ? Please, feel free to let us know.', 'fael'); ?></p>
            <a href="https://cybercraftit.com/contact" target="_blank" class="help-link"><?php _e( 'Contact us', 'fael'); ?></a>
        </div>
    </article>
    <article class="card">
        <div class="text">
            <div class="help-icon"><i class="dashicons dashicons-admin-site"></i></div>
            <h3><?php _e( 'Let People Know', 'fael' ); ?></h3>
            <p><?php _e( 'Got this plugin great ? We highly  appriciate sharing it with others.', 'fael'); ?></p>
            <a href="https://facebook.com/" target="_blank"><?php _e( 'Facebook', 'fael'); ?></a>
            <a href="https://twitter.com/" target="_blank"><?php _e( 'Twitter', 'fael'); ?></a>
        </div>
    </article>
</main>