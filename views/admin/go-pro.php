<!doctype html>
<title>Example</title>
<style>
    .go-pro-btn:hover{
        color: #ffffff;
        background: #780031;
    }
    .go-pro-btn{
        display: block;
        padding: 15px 0px;
        background: #91003B;
        color: #ffffff;
        font-weight: bold !important;
        width: 100%;
        text-align: center;
        text-decoration: none;
        font-size: 15px;
    }
    .cards {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        width: 100%;
        margin-bottom:20px;
    }
    .card {
        display: flex;
        flex-direction: column;
        flex-basis: 100%;
        flex: 1;
    }
    .card img {
        max-width: 100%;
    }
    .card .text {
        padding: 0 20px 20px;
    }
    .card .text > button {
        background: gray;
        border: 0;
        color: white;
        padding: 10px;
        width: 100%;
    }
</style>

<main>
    <div class="cards">
        <article class="card">
            <div class="text">
                <h3><?php _e( 'Widget and Fields Access Control'); ?></h3>
                <p><?php _e( 'With Pro, Each and every widget of elementor will have accessibility option through which you can control the access permission for any widget', 'fael' ); ?></p>
            </div>
        </article>
        <article class="card">
            <div class="text">
                <h3><?php _e( 'Content creation by guest'); ?></h3>
                <p><?php _e( 'Logged out/guest user can create post, page or any content' ); ?></p>
            </div>
        </article>
        <article class="card">
            <div class="text">
                <h3><?php _e( 'Advanced Item Listing'); ?></h3>
                <p><?php _e( 'List posts/contents of any author(s)' ); ?></p>
            </div>
        </article>
        <article class="card">
            <div class="text">
                <h3><?php _e( 'Action Control'); ?></h3>
                <p><?php _e( 'Control the actions as how you want (add , edit, delete) for the posts listed' ); ?></p>
            </div>
        </article>
        <article class="card">
            <div class="text">
                <h3><?php _e( 'More advanced widgets', 'fael' ); ?></h3>
                <p><?php _e( 'Our pro version will give the access to more advanced and stunning field and display widgets.'); ?></p>
            </div>
        </article>
        <article class="card">
            <div class="text">
                <h3><?php _e( 'More Form Types', 'fael' ); ?></h3>
                <p><?php _e( 'Along with other form types, custom taxonomy form and settings are there in pro version'); ?></p>
            </div>
        </article>
        <article class="card">
            <div class="text">
                <h3><?php _e( 'Premium Support', 'fael' ); ?></h3>
                <p><?php _e( 'Premium support will be unlocked for you using pro version to get the solution of any query effectively.'); ?></p>
            </div>
        </article>
        <article class="card">
            <div class="text">
                <h3><?php _e( 'Pro Update'); ?></h3>
                <p><?php _e( 'Instant and regular pro update will be available to you.'); ?></p>
            </div>
        </article>
    </div>
    <a class="go-pro-btn" href="https://cybercraftit.com/user-frontend-for-elementor-pro/" target="_blank"><?php _e('Buy Now / Learn More', 'ufel'); ?></a>
</main>