<?php /* Template Name: Kiosk Products */ ?>
<?php
get_header();
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-3">
            <ul class="list-group">
                <li class="list-group-item" data-filter=".starters">Starters</li>
                <li class="list-group-item" data-filter=".salads">Salads</li>
                <li class="list-group-item" data-filter=".pastas">Pastas</li>
                <li class="list-group-item" data-filter=".pizzas">Pizzas</li>
                <li class="list-group-item" data-filter=".combos">Combos</li>
                <li class="list-group-item" data-filter=".deserts">Desserts</li>
                <li class="list-group-item" data-filter=".beverages">Beverages</li>
            </ul>
        </div>
        <div class="col-9">
            <div class="card-deck">
                <div class="card" style="width: 18rem;" data-cat="salads">
                    <img class="card-img-top" src="http://localhost/pizzademo2/wp-content/uploads/2022/07/89a49c4094c548c18b2c9aba18420637.png" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">$2.80 - $3.00</p>
                        </div>
                </div>
                <div class="card" style="width: 18rem;" data-cat="pizzas">
                    <img class="card-img-top" src="http://localhost/pizzademo2/wp-content/uploads/2022/07/415d2ebaeacf40a68b1a389d5dcbcd12.png" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">$2.80 - $3.00</p>
                        </div>
                </div>
                </div>
            </div>
    </div> 
</div>
<?php
get_footer();