

<?php session_start()  ?>
<div class="container-top top-bar primary">
    <div class="col-6">
        <div class="button-back" onClick="restartCart(this,'http://wordpress2.cbs.local/')">
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
        <div id="productsdiv" class="col-9">
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
