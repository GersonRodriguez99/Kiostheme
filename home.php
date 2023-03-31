<?php /* Template Name: Kiosk Home */ ?>
<?php
get_header();

?>
<style>
 .home {
    color: #212529;
}

td.product-total{
    text-align: right;
}
tr.cart-subtotal > td:nth-child(2){
    text-align: right;
}
tr.fee > td:nth-child(2){
    text-align: right;
}
tr.order-total > td:nth-child(2){
    text-align: right;
}   
</style>
<div id="primary" class="content-area">
    <main id="main" class="site-main home-screen">
        <?php while(have_posts()) : the_post(); ?>
        <section  class="home-container">
            <?php ?>
            <div class="overlay-bg" >
            <h1 class="kiosk-main-title" >Order Here</h1>
            <h3 class="kiosk-sub-title" > Tap anywhere to begin </h3>
            </div>
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

        </section>
        <?php endwhile;?>
    </main><!-- #main -->
    <div style="display: none;" id="mainDiv">


<div class="container-top top-bar primary">
    <div class="col-6">
        <div class="button-back" onClick="restartCart(this,'<?=get_home_url();?>')">
        <i class="fa-solid fa-arrow-left"></i><h1>Start Again</h1> 
        </div>
    </div>
    <div class="col-6"> 


    </div>
</div>
<div class="container-top top-bar">
    <div class="row container">
        <div class="col-3">
            <div class="top-bar-item"> <button onclick="openDropdown()" class="dropbtn">View my order <i class="fa-solid fa-caret-down"></i></button></div>
        </div>
        <div class="col-9">
            <div class="top-bar-item-right"> 
                <div id="item-price-global" class="price" onclick="openDropdown()">
                    $0.00
                </div>
                <a id="cart-icon" class="cart" onclick="openDropdown()">
                <i class="fa-solid fa-cart-shopping"></i>
                <div class="cart-number">0</div>
                </a>
            </div> 
        </div>    
    </div>
</div>
<div class="dropdown">
  <div id="myDropdown" class="dropdown-content">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="cart-box">
                    <div class="row">
                        <div class="col-12 text-center">Empty Cart</div>
                    </div> 
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-12">
            <div class="cart-box-total">
                <div class="row" >
                    <div class="col-6">

                    </div>
                    <div class="col-6">
                        <div class="row ">
                            <div class="col-12 item-price">
                                <div class="label">
                                    Subtotal
                                </div>
                                <div id="cart-subtotal" class="number">
                                $0.00
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-12 item-price">
                                <div class="label">
                                    TAX
                                </div>
                                <div id="cart-tax" class="number">
                                $0.00
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-12 item-price total">
                                <div  class="label-total">
                                    Total
                                </div>
                                <div id="cart-total" class="number">
                                $0.00
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-12">
            <div class="cart-box-button">
                <button onclick="sendToCheckout()">Payment</button>
            </div>
        </div>
        </div>
    </div>
  </div>
</div><!-- end-top-bar -->
<div class="main-container">
<div class="container-fluid">
    <div class="row">
        <div id="categoriesdiv" class="col-3">
        </div>
        <div class="col-9 products">
  
        <div id="productsdiv" class="col-12">
        <div id="card-deck" class="card-deck">
        <div class="card item" style="display: none;"> 
                        <a href="#" class="modal-opener" onClick="openModal(this)">
                        <img class="card-img-top" src="" alt="Card image cap">
                        <div class="card-body">
                        <h5 class="card-title"></h5>
                        <div class="card-text"></div>
                        </div>
                        </a>
                        </div>
        </div>  
        
        </div>
        <div style="display: none;margin-top:50px;" class="loading">
        <div class="d-flex justify-content-center">
            <div class="spinner-grow text-secondary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        </div>
   
        </div>
        
    </div>
</div>
<div id="modal-container" class="modal-container-ajax"> 
    <div class="jquery-modal blocker current">
        <div id="main-modal" class="modal" id="ex1" style="display: inline-block;">
    </div>
</div> 
</div>
</div>

    </div>
</div><!-- #primary -->
<div id='imgloading'  class="loader" style="display:none;">
    <img style='position:absolute' src='<?php echo get_template_directory_uri() . '/assets/images/loading-25.gif'; ?>'>
</div>
<div id="preorder" class="main-menu-container" style="display: none;">
    <div class="container">
        <div class="row title">
            What do you want to create?
        </div>
        <div class="row">
            <div id="dinein" onclick="dinein()" class="button button-1">
                <svg width="81" height="80" viewBox="0 0 81 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M40.021 1.45042e-05C30.3784 -0.00530977 21.0585 3.47289 13.777 9.79427C6.49551 16.1156 1.7427 24.8546 0.393547 34.4024C-0.955602 43.9501 1.18974 53.6639 6.43461 61.7553C11.6795 69.8467 19.6708 75.7711 28.9373 78.4378V54.4944C25.5934 53.5065 23.1215 50.0746 23.1215 45.9947V27.9992C23.1894 27.5225 23.4271 27.0863 23.7908 26.7707C24.1545 26.4551 24.6199 26.2814 25.1015 26.2814C25.583 26.2814 26.0484 26.4551 26.4121 26.7707C26.7758 27.0863 27.0135 27.5225 27.0814 27.9992V40.8948C27.09 41.1273 27.1828 41.3488 27.3425 41.518C27.5021 41.6872 27.7178 41.7927 27.9494 41.8148C28.0796 41.8164 28.2088 41.792 28.3294 41.7429C28.4499 41.6938 28.5595 41.6211 28.6516 41.529C28.7436 41.437 28.8163 41.3274 28.8654 41.2068C28.9145 41.0862 28.939 40.957 28.9373 40.8268V27.9752C29.0053 27.4985 29.2429 27.0623 29.6067 26.7467C29.9704 26.4311 30.4358 26.2574 30.9173 26.2574C31.3988 26.2574 31.8642 26.4311 32.2279 26.7467C32.5917 27.0623 32.8293 27.4985 32.8972 27.9752V40.9868C32.8972 41.2329 32.995 41.469 33.169 41.643C33.3431 41.817 33.5791 41.9148 33.8252 41.9148C34.0713 41.9148 34.3074 41.817 34.4814 41.643C34.6554 41.469 34.7532 41.2329 34.7532 40.9868C34.7572 40.9349 34.7572 40.8828 34.7532 40.8308V27.9752C34.8211 27.4985 35.0588 27.0623 35.4225 26.7467C35.7862 26.4311 36.2516 26.2574 36.7331 26.2574C37.2147 26.2574 37.68 26.4311 38.0438 26.7467C38.4075 27.0623 38.6451 27.4985 38.7131 27.9752V45.9987C38.7131 50.0786 36.2411 53.5105 32.8972 54.4984V79.3657C37.9039 80.2646 43.0354 80.2076 48.0208 79.1977V52.8385C44.1409 51.3785 41.257 45.9387 41.257 39.4549C41.257 31.8551 45.2249 25.6753 50.1207 25.6753C55.0166 25.6753 58.9885 31.8471 58.9885 39.4549C58.9885 45.7587 56.2686 51.0545 52.5447 52.7105V77.9978C61.584 75.0267 69.2681 68.9305 74.2173 60.8039C79.1665 52.6772 81.0565 43.0524 79.5481 33.6577C78.0396 24.2629 73.2315 15.7136 65.987 9.54485C58.7424 3.37608 49.5361 -0.00808476 40.021 1.45042e-05Z" fill="#2175C8"/>
                <path d="M38.7131 27.9752V45.9987C38.7131 50.0786 36.2411 53.5105 32.8972 54.4984V79.3657C31.5622 79.1263 30.2405 78.8179 28.9373 78.4417V54.4944C25.5934 53.5065 23.1215 50.0746 23.1215 45.9947V27.9992C23.1894 27.5225 23.4271 27.0863 23.7908 26.7707C24.1545 26.4551 24.6199 26.2814 25.1015 26.2814C25.583 26.2814 26.0484 26.4551 26.4121 26.7707C26.7758 27.0863 27.0135 27.5225 27.0814 27.9992V40.7308C27.0774 40.7854 27.0774 40.8402 27.0814 40.8948C27.09 41.1273 27.1828 41.3488 27.3425 41.518C27.5021 41.6872 27.7178 41.7927 27.9494 41.8148C28.0796 41.8164 28.2088 41.792 28.3294 41.7429C28.4499 41.6938 28.5595 41.6211 28.6516 41.529C28.7436 41.437 28.8163 41.3274 28.8654 41.2068C28.9145 41.0862 28.939 40.957 28.9373 40.8268V27.9752C29.0053 27.4985 29.2429 27.0623 29.6067 26.7467C29.9704 26.4311 30.4358 26.2574 30.9173 26.2574C31.3988 26.2574 31.8642 26.4311 32.2279 26.7467C32.5917 27.0623 32.8293 27.4985 32.8972 27.9752V40.7988C32.8872 40.8598 32.8872 40.9219 32.8972 40.9828C32.8972 41.229 32.995 41.465 33.169 41.639C33.3431 41.813 33.5791 41.9108 33.8252 41.9108C34.0713 41.9108 34.3074 41.813 34.4814 41.639C34.6554 41.465 34.7532 41.229 34.7532 40.9828C34.7572 40.9309 34.7572 40.8788 34.7532 40.8268V27.9752C34.8211 27.4985 35.0588 27.0623 35.4225 26.7467C35.7862 26.4311 36.2516 26.2574 36.7331 26.2574C37.2147 26.2574 37.68 26.4311 38.0438 26.7467C38.4075 27.0623 38.6451 27.4985 38.7131 27.9752Z" fill="white"/>
                <path d="M58.9845 39.4549C58.9845 45.7587 56.2646 51.0545 52.5407 52.7105V77.9978C51.0607 78.4921 49.5511 78.8929 48.0208 79.1977V52.8385C44.1409 51.3785 41.257 45.9387 41.257 39.4549C41.257 31.8551 45.2249 25.6753 50.1207 25.6753C55.0166 25.6753 58.9845 31.8471 58.9845 39.4549Z" fill="white"/>
                </svg> 
                Dine In
            </div>
            <div class="button button-2" onclick="takeout()">
                <svg width="67" height="80" viewBox="0 0 67 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_16_132)">
                <path d="M66.3015 77.9159C66.3148 78.1841 66.2736 78.4523 66.1803 78.7041C66.087 78.956 65.9436 79.1863 65.7588 79.3811C65.5739 79.5759 65.3515 79.7312 65.1049 79.8376C64.8583 79.944 64.5927 79.9992 64.3241 80H1.98562C1.71599 79.9998 1.44921 79.9448 1.20156 79.8381C0.953907 79.7315 0.730566 79.5755 0.545157 79.3797C0.359748 79.184 0.216155 78.9525 0.123132 78.6994C0.0301079 78.4463 -0.0103968 78.1769 0.00408791 77.9077L2.92511 23.0687C2.95244 22.5608 3.17364 22.0827 3.5431 21.7331C3.91256 21.3835 4.4021 21.189 4.91075 21.1897H14.8307V30.1415C14.8131 30.7111 14.91 31.2784 15.1157 31.8097C15.3214 32.3411 15.6318 32.8257 16.0284 33.2349C16.425 33.644 16.8998 33.9693 17.4245 34.1914C17.9492 34.4135 18.5133 34.528 19.0831 34.528C19.6529 34.528 20.2169 34.4135 20.7416 34.1914C21.2663 33.9693 21.7411 33.644 22.1377 33.2349C22.5343 32.8257 22.8447 32.3411 23.0504 31.8097C23.2562 31.2784 23.3531 30.7111 23.3354 30.1415V21.1897H43.1754V30.1415C43.1577 30.7111 43.2546 31.2784 43.4603 31.8097C43.666 32.3411 43.9764 32.8257 44.373 33.2349C44.7697 33.644 45.2444 33.9693 45.7691 34.1914C46.2939 34.4135 46.8579 34.528 47.4277 34.528C47.9975 34.528 48.5615 34.4135 49.0862 34.1914C49.6109 33.9693 50.0857 33.644 50.4823 33.2349C50.8789 32.8257 51.1893 32.3411 51.395 31.8097C51.6008 31.2784 51.6977 30.7111 51.68 30.1415V21.1897H61.6164C62.1253 21.1902 62.6145 21.3865 62.9826 21.7378C63.3507 22.0891 63.5696 22.5686 63.5938 23.0769L66.3015 77.9159Z" fill="#2175C8"/>
                <path d="M49.4113 16.1559V30.1415C49.4235 30.4096 49.3812 30.6774 49.287 30.9287C49.1928 31.18 49.0487 31.4095 48.8633 31.6036C48.6779 31.7976 48.4551 31.952 48.2084 32.0575C47.9616 32.163 47.696 32.2174 47.4277 32.2174C47.1593 32.2174 46.8938 32.163 46.647 32.0575C46.4003 31.952 46.1775 31.7976 45.9921 31.6036C45.8067 31.4095 45.6625 31.18 45.5684 30.9287C45.4742 30.6774 45.4319 30.4096 45.4441 30.1415V16.1559C45.4738 14.5367 45.1805 12.9278 44.5814 11.4233C43.9823 9.91868 43.0894 8.54858 41.9548 7.39299C40.8203 6.2374 39.4668 5.31949 37.9734 4.6929C36.4801 4.0663 34.8769 3.74357 33.2574 3.74357C31.638 3.74357 30.0347 4.0663 28.5414 4.6929C27.0481 5.31949 25.6946 6.2374 24.56 7.39299C23.4254 8.54858 22.5325 9.91868 21.9334 11.4233C21.3343 12.9278 21.041 14.5367 21.0708 16.1559V30.1415C21.0708 30.6682 20.8616 31.1732 20.4892 31.5456C20.1168 31.918 19.6117 32.1272 19.0851 32.1272C18.5585 32.1272 18.0534 31.918 17.6811 31.5456C17.3087 31.1732 17.0995 30.6682 17.0995 30.1415V16.1559C17.0995 11.8711 18.8016 7.76177 21.8314 4.73195C24.8612 1.70213 28.9706 0 33.2554 0C37.5402 0 41.6495 1.70213 44.6793 4.73195C47.7091 7.76177 49.4113 11.8711 49.4113 16.1559Z" fill="#2175C8"/>
                <path d="M49.4113 16.1559V30.1415C49.4235 30.4096 49.3812 30.6774 49.287 30.9287C49.1928 31.18 49.0487 31.4095 48.8633 31.6036C48.6779 31.7976 48.4551 31.952 48.2084 32.0575C47.9616 32.163 47.696 32.2174 47.4277 32.2174C47.1593 32.2174 46.8938 32.163 46.647 32.0575C46.4003 31.952 46.1775 31.7976 45.9921 31.6036C45.8067 31.4095 45.6625 31.18 45.5684 30.9287C45.4742 30.6774 45.4319 30.4096 45.4441 30.1415V16.1559C45.4738 14.5367 45.1805 12.9278 44.5814 11.4233C43.9823 9.91868 43.0894 8.54858 41.9548 7.39299C40.8203 6.2374 39.4668 5.31949 37.9734 4.6929C36.4801 4.0663 34.8769 3.74357 33.2574 3.74357C31.638 3.74357 30.0347 4.0663 28.5414 4.6929C27.0481 5.31949 25.6946 6.2374 24.56 7.39299C23.4254 8.54858 22.5325 9.91868 21.9334 11.4233C21.3343 12.9278 21.041 14.5367 21.0708 16.1559V30.1415C21.0708 30.6682 20.8616 31.1732 20.4892 31.5456C20.1168 31.918 19.6117 32.1272 19.0851 32.1272C18.5585 32.1272 18.0534 31.918 17.6811 31.5456C17.3087 31.1732 17.0995 30.6682 17.0995 30.1415V16.1559C17.0995 11.8711 18.8016 7.76177 21.8314 4.73195C24.8612 1.70213 28.9706 0 33.2554 0C37.5402 0 41.6495 1.70213 44.6793 4.73195C47.7091 7.76177 49.4113 11.8711 49.4113 16.1559Z" fill="#2175C8"/>
                </g>
                <defs>
                <clipPath id="clip0_16_132">
                <rect width="66.2974" height="80" fill="white"/>
                </clipPath>
                </defs>
                </svg>

                Take out Order
            </div>
            <div class="button button-3" onclick="preorder()">
                <svg width="67" height="80" viewBox="0 0 67 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_20_147)">
                <path d="M60.0221 1.96291V45.3024C56.2911 42.7996 51.8267 41.6244 47.3471 41.966C42.8674 42.3076 38.6328 44.146 35.3245 47.1856C32.0161 50.2251 29.8263 54.2892 29.1073 58.7239C28.3883 63.1587 29.1818 67.7064 31.3603 71.6356H1.96291C1.44231 71.6356 0.943039 71.4288 0.574923 71.0607C0.206806 70.6925 0 70.1933 0 69.6727V1.96291C0 1.44231 0.206806 0.943039 0.574923 0.574923C0.943039 0.206806 1.44231 0 1.96291 0H58.0592C58.3169 0 58.5722 0.0507727 58.8103 0.149418C59.0485 0.248063 59.2649 0.39265 59.4471 0.574923C59.6294 0.757195 59.774 0.973583 59.8727 1.21173C59.9713 1.44988 60.0221 1.70513 60.0221 1.96291Z" fill="#2175C8"/>
                <path d="M48.8794 79.1593C45.4709 79.1593 42.1391 78.1486 39.3051 76.255C36.4711 74.3614 34.2623 71.6699 32.9579 68.521C31.6536 65.372 31.3123 61.907 31.9773 58.5641C32.6422 55.2212 34.2835 52.1505 36.6936 49.7404C39.1037 47.3303 42.1744 45.689 45.5173 45.024C48.8602 44.3591 52.3253 44.7003 55.4742 46.0047C58.6232 47.309 61.3146 49.5178 63.2083 52.3518C65.1019 55.1858 66.1126 58.5177 66.1126 61.9261C66.1126 66.4966 64.2969 70.88 61.0651 74.1118C57.8332 77.3437 53.4499 79.1593 48.8794 79.1593ZM48.8794 48.6397C46.2487 48.6389 43.6769 49.4183 41.4892 50.8792C39.3016 52.3402 37.5964 54.4172 36.5893 56.8475C35.5822 59.2777 35.3185 61.952 35.8315 64.5322C36.3445 67.1123 37.6112 69.4824 39.4713 71.3425C41.3315 73.2027 43.7015 74.4694 46.2817 74.9824C48.8618 75.4954 51.5362 75.2317 53.9664 74.2246C56.3966 73.2175 58.4736 71.5123 59.9346 69.3246C61.3956 67.137 62.175 64.5652 62.1742 61.9345C62.1697 58.4099 60.7676 55.0309 58.2753 52.5386C55.783 50.0463 52.404 48.6442 48.8794 48.6397Z" fill="#2175C8"/>
                <path d="M62.1741 61.9345C62.1741 64.564 61.3944 67.1344 59.9335 69.3207C58.4727 71.507 56.3963 73.2111 53.967 74.2173C51.5377 75.2236 48.8645 75.4868 46.2856 74.9739C43.7067 74.4609 41.3377 73.1947 39.4784 71.3354C37.6191 69.476 36.3529 67.1071 35.8399 64.5282C35.327 61.9493 35.5902 59.2761 36.5965 56.8468C37.6027 54.4175 39.3068 52.3411 41.4931 50.8803C43.6794 49.4194 46.2498 48.6397 48.8793 48.6397C52.4036 48.6453 55.7819 50.0478 58.274 52.5398C60.766 55.0319 62.1685 58.4102 62.1741 61.9345Z" fill="white"/>
                <path d="M46.0464 16.5565H13.9716C13.451 16.5565 12.9517 16.3497 12.5836 15.9816C12.2155 15.6134 12.0087 15.1142 12.0087 14.5936C12.0087 14.073 12.2155 13.5737 12.5836 13.2056C12.9517 12.8375 13.451 12.6307 13.9716 12.6307H46.0464C46.567 12.6307 47.0663 12.8375 47.4344 13.2056C47.8025 13.5737 48.0093 14.073 48.0093 14.5936C48.0093 15.1142 47.8025 15.6134 47.4344 15.9816C47.0663 16.3497 46.567 16.5565 46.0464 16.5565Z" fill="white"/>
                <path d="M46.0464 27.7749H13.9716C13.451 27.7749 12.9517 27.5681 12.5836 27.2C12.2155 26.8319 12.0087 26.3326 12.0087 25.812C12.0087 25.2914 12.2155 24.7922 12.5836 24.424C12.9517 24.0559 13.451 23.8491 13.9716 23.8491H46.0464C46.567 23.8491 47.0663 24.0559 47.4344 24.424C47.8025 24.7922 48.0093 25.2914 48.0093 25.812C48.0093 26.3326 47.8025 26.8319 47.4344 27.2C47.0663 27.5681 46.567 27.7749 46.0464 27.7749Z" fill="white"/>
                <path d="M32.2388 38.9933H13.9716C13.451 38.9933 12.9517 38.7865 12.5836 38.4184C12.2155 38.0503 12.0087 37.551 12.0087 37.0304C12.0087 36.5098 12.2155 36.0105 12.5836 35.6424C12.9517 35.2743 13.451 35.0675 13.9716 35.0675H32.2388C32.7594 35.0675 33.2587 35.2743 33.6268 35.6424C33.9949 36.0105 34.2017 36.5098 34.2017 37.0304C34.2017 37.551 33.9949 38.0503 33.6268 38.4184C33.2587 38.7865 32.7594 38.9933 32.2388 38.9933Z" fill="white"/>
                <path d="M48.8793 63.2502C48.3587 63.2502 47.8594 63.0434 47.4913 62.6752C47.1232 62.3071 46.9164 61.8078 46.9164 61.2873V55.159C46.9164 54.6384 47.1232 54.1391 47.4913 53.771C47.8594 53.4029 48.3587 53.196 48.8793 53.196C49.3999 53.196 49.8992 53.4029 50.2673 53.771C50.6354 54.1391 50.8422 54.6384 50.8422 55.159V61.2873C50.8422 61.545 50.7914 61.8003 50.6928 62.0384C50.5941 62.2766 50.4495 62.493 50.2673 62.6752C50.085 62.8575 49.8686 63.0021 49.6305 63.1007C49.3923 63.1994 49.1371 63.2502 48.8793 63.2502Z" fill="#2175C8"/>
                <path d="M53.1456 68.4033C52.8565 68.4044 52.5708 68.3413 52.3091 68.2185C52.0474 68.0956 51.8163 67.9161 51.6325 67.6929L47.3662 62.5356C47.0346 62.1343 46.8759 61.6177 46.9252 61.0994C46.9745 60.5811 47.2276 60.1036 47.6289 59.772C48.0302 59.4403 48.5469 59.2817 49.0651 59.331C49.5834 59.3802 50.0609 59.6334 50.3925 60.0347L54.6588 65.192C54.8954 65.4791 55.0456 65.8276 55.0917 66.1967C55.1379 66.5659 55.0782 66.9405 54.9197 67.2771C54.7611 67.6136 54.5101 67.8982 54.196 68.0976C53.882 68.297 53.5177 68.403 53.1456 68.4033Z" fill="#2175C8"/>
                </g>
                <defs>
                <clipPath id="clip0_20_147">
                <rect width="66.9448" height="80" fill="white"/>
                </clipPath>
                </defs>
                </svg>

                Pre-Order
            </div>
        </div>
    </div>
</div>
<div id="guestIdentifier" class="main-menu-container" style="display: none;">
    <div class="container">
    <div class="card w-75" style="text-align:center ;">
  <div class="card-body" style="padding-bottom: 100px ;">

  <label for="exampleFormControlInput1" style="color: black;font-size: xxx-large;" class="form-label">Phone number</label>
  <input  id="guestPhone" name="guestPhone" type="text" class="form-control" aria-label="Guess identifier" aria-describedby="inputGroup-sizing-default">
  <div class="d-grid gap-2">
  <button id="guestBtn" class="btn btn-primary btn-lg" style="background-color:#2175c8" type="button">OK!</button>
</div>
 </div>
    </div>
</div>
</div>
<?php 
function get_random_featured_image() {
    $link = array();
    $arguments = array(
        'post_type' => 'product',
        'orderby' => 'rand',
        'meta_key' => '_thumbnail_id', //this grabs a random post
        'posts_per_page' => 4,
    );
    $query = new WP_Query($arguments);
    while($query->have_posts()) {
        $query->the_post();
        $image_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium' )[0];
        array_push($link , $image_src);
    }
    wp_reset_postdata();
    return $link; //return the image url
}?>
<?php
get_footer();
$session_tax=(isset($_SESSION['TAX']))?$_SESSION['TAX']:''; ?>
<script type="text/javascript">
    function get_tax_session (){
        var taxes ='<?php echo $session_tax;?>';
        localStorage.setItem('tax', taxes);
        return taxes;
    }
</script>