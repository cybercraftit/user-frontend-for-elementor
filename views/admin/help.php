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
    .card .text > button {
        background: gray;
        border: 0;
        color: white;
        padding: 10px;
        width: 100%;
    }
</style>

<main class="cards">
    <article class="card">
        <div class="text">
            <h3><?php _e( 'Widget Access Control'); ?></h3>
            <p><?php _e( 'With Pro, Each and every widget of elementor will have accessibility option through which you can control the access permission for any widget', 'fael' ); ?></p>
        </div>
    </article>
    <article class="card">
        <div class="text">
            <h3><?php _e( 'More advanced widgets', 'fael' ); ?></h3>
            <p><?php _e( 'Our pro version will give the access to more advanced and stunning widgets.'); ?></p>
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
            <h3><?php _e( 'Regular Pro Update'); ?></h3>
            <p><?php _e( 'Instant and regular pro update will be available to you.'); ?></p>
        </div>
    </article>