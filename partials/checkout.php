<?php /* Template Name: Checkout */ ?>
<?php
get_header();
?>
<div class="container checkout">
    <div class="check-container ">
        <div class="checkout-header">
            <h1>Check Number : 1213414 </h1>

        </div>
        <div class="checkout-content">
        <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
            <thead>
                <tr>
                    <th class="woocommerce-table__product-name product-name">Product</th>
                    <th class="woocommerce-table__product-table product-total">Total</th>
                </tr>
            </thead>
		<tbody>
			<tr class="woocommerce-table__line-item order_item">
	            <td class="woocommerce-table__product-name product-name">
		            <a href="http://69.164.204.126/product/chips-and-salsa/?attribute_pa_choose-your-size=large">Chips and Salsa - Large</a> <strong class="product-quantity">×&nbsp;1</strong>	</td>
                    <td class="woocommerce-table__product-total product-total">
		            <span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>6.99</bdi></span>	
                </td>
            </tr>
		</tbody>
		<tfoot>
			<tr>
			    <th scope="row">Subtotal:</th>
				    <td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>6.99</span></td>
			</tr>
			<tr>
				<th scope="row">TAX:</th>
					<td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>0.61</span></td>
			</tr>
			<tr>
				<th scope="row">Payment method:</th>
					<td>Cash on delivery</td>
				</tr>
			<tr>
				<th scope="row">Total:</th>
					<td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>7.60</span></td>
			</tr>
		</tfoot>
	</table>
    </div>
    <div class="checkout-footer">
        <button class="finalize-button" onClick="remove_items_from_cart()"> Finalize</button>
    </div>
    </div>
</div>

<?php
get_footer();