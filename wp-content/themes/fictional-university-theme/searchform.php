
<form class="search-form" method="get" action="<?php echo esc_url( site_url('/') ); ?>">
    <label class="headline headline--medium" for="s">Perform a New Search:</label>
    <div class="search-form-row">
      <input id="s" class="s" type="search" name="s" placeholder="What are you looking for?">
      <input class="search-submit" type="submit" value="search">
    </div>
</form>