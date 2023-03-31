<section class="home-container">
            <?php ?>
            <h1 class="kiosk-main-title" >Order Here</h1>
            <div class="splide" role="group" aria-label="Splide Basic HTML Example">
                <div class="splide__track">
                    <ul class="splide__list">
                        <?php foreach (get_random_featured_image() as $key => $value) {?>
                            <li class="splide__slide">
                                <div class="splide__slide__container">
                                    <img src="<?php echo $value;?>">
                                </div>
                            </li>
                            <?php } ?>
                    </ul>
                </div>
            </div>
        <?php?>
</section>