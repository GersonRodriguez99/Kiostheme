var touching=false;
var timeout=kiosk_vars_object.timeout;
var actualCat=0;
var noCat=0;

 jQuery(document).on('click',function(e){
 touching=true;
 timeout=kiosk_vars_object.timeout;
 });

 window.setInterval(escribir, 1000);
 //restartCart
 function escribir(){
    if(touching){
      touching=false;
    }else{
        if(timeout>0){
          if(timeout==10){
            timeOutWarning();
          }
          if(timeout==-1)
          {
            timeout=kiosk_vars_object.timeout+1;
          }
          timeout=timeout-1;
          jQuery("#timeout-div").html(`<h1>`+timeout+`</h1>`);

        }else{
          restartCart(this,kiosk_vars_object.home_url)
        }
    }
 }
 function sendToCheckout()
 {
  noCat=1;
  var ordertype = localStorage.getItem("ordertype");
   openDropdown();
   spinnerON();
   jQuery("#productsdiv").html(' ');
  jQuery.ajax({
     type: "POST",
     url: wc_add_to_cart_params.ajax_url,
     data: {
       action: 'checkout_kiosk',
       ordertype : ordertype
     },
     success: function (data) {
      jQuery("#productsdiv").html('');
       jQuery("#productsdiv").html(data);

         var dropdowns = document.getElementsByClassName("dropdown-content");
         var i;
         for (i = 0; i < dropdowns.length; i++) {
           var openDropdown = dropdowns[i];
           if (openDropdown.classList.contains('show')) {
             openDropdown.classList.remove('show');
           }
         }
     }
 }).done(function(){
   spinnerOFF();
   jQuery(".woocommerce-billing-fields").html("");
   localStorage.removeItem("ordertype");
 });  
 
 }
 var compoPrice =0;
 jQuery.fn.riseUp = function()   { jQuery(this).show("slide", { direction: "down" }, 1000); }
function generatePayload(type=false)
{
  var payload="" ;
  var siteid = kiosk_vars_object.data_var_1;
  items = jQuery(".components-items-container").length;
  var i = 1;
  jQuery(".components-items-container").each(function (event) {

    $(this).find('a.active').each(function (e) {

      //  console.log($(this).data('category'));
      var price = jQuery(this).data("price");
      var loc = jQuery(this).data("position");
      var locStr = (loc != "" ? "_" + loc : "");

      if (type) {

        payload = payload + "" + "10" + "###" + jQuery(this).data('compname') + "###" + i + "###" + (price > 0 ? price : "") + "######" + siteid + "###" + jQuery(this).data('componentid') + locStr + "@@@" + jQuery(this).data('quantity') + ",";
      }
      else {
        payload = payload + "" + "10" + "###" + jQuery(this).data('compname') + "###" + i + "###" + (price > 0 ? price : "") + "######" + siteid + "###" + jQuery(this).data('componentid') + locStr + ",";
        compoPrice = parseFloat(compoPrice) + parseFloat(price);
      }
      console.log(jQuery(this));
      console.log(loc);
      console.log(locStr);
      console.log(jQuery(this).data('quantity'));

      i++;
    });
  });
  var j = payload.length - 1
  response = payload.slice(0, j)
  return response;
}

var rulesGlobal = [];
var dataGlobal = [];
var valueGlobal = [];
var selectedComp = [];

jQuery("#list-cat li").click(function (event) {
  console.log("error");
  event.preventDefault();
});
jQuery('#main').click(function () {
  jQuery('#main').hide();

  jQuery('#guestIdentifier').show();
  /*jQuery.get("wp-content/themes/theme-cbs-kiosk/partials/main.php", function (data) {
    jQuery("#primary").html(data);
 //   getCategories();
   getAllProducts();

  });*/
  getCategories();

  //getAllProducts();
});

function dinein(){
 /* console.log("dinein");
  jQuery.get("wp-content/themes/theme-cbs-kiosk/partials/main.php", function (data) {
    jQuery("#primary").html(data);
    getCategories();
  });*/
  jQuery('#preorder').hide();
  jQuery('#mainDiv').show();
  localStorage.setItem("ordertype", "DineIn");
}
function takeout(){
 /* console.log("dinein");
  jQuery.get("wp-content/themes/theme-cbs-kiosk/partials/main.php", function (data) {
    jQuery("#primary").html(data);
    getCategories();
  });*/
  jQuery('#preorder').hide();
  jQuery('#mainDiv').show();
  localStorage.setItem("ordertype", "TakeOut");
}
function preorder(){
  /*console.log("dinein");
  jQuery.get("wp-content/themes/theme-cbs-kiosk/partials/main.php", function (data) {
    jQuery("#primary").html(data);
    getCategories();
  });*/

  jQuery('#preorder').hide();
  jQuery('#mainDiv').show();

  localStorage.setItem("ordertype", "PreOrder");
  
}
let count = 0;
function getSingleProductComponents(id = null) {
  data_result = '';
  jQuery.ajax({
    type: "POST",
    url: wc_add_to_cart_params.ajax_url,
    data: {
      action: 'single_product_components_kiosk',
      product_id: id

    },
    success: function (data) {


      //  valueGlobal[value.ID] = value;
      dataGlobal[id] = data;
      /* create_modal(value, data); */
    }
  });
}


var categories_kiosk;
function getCategories() {
  spinnerON();
  jQuery.ajax({
    type: "POST",
    url: wc_add_to_cart_params.ajax_url,
    data: {
      action: 'get_categories_kiosk',
      siteid: kiosk_vars_object.data_var_1

    },
    success: function (data) {
      categories_kiosk=data;
      var i=0;
      var output;
      output = "<div class='list-group'>";
      jQuery.each(data, function (key, value) {

        output += ' <div onclick="getProductByCategory('+value.term_id+','+i+',false)" data-termid="' + value.term_id + '" class="list-group-item  category-item">' + value.name + '</div>';
        i++;
      });
      output += '</div>';
      jQuery("#categoriesdiv").html(output);
     /* jQuery('.category-item').click(function () {
        jQuery('.category-item').removeClass("active");
        jQuery(this).addClass("active");
        var catid = jQuery(this).attr('data-termid');
        var catid = jQuery(this).attr('data-termid');
        getProductByCategory(catid);
      });*/

    },
    error: function (request, status, error) {
      console.log(request.responseText);
  }
  }).done(function () {
    getCart();
 //   getCategoryDefault();
    spinnerOFF();
    getProductByCategory(categories_kiosk[actualCat].term_id,actualCat,false);
  });

}
function getCategoryDefault() {
  var id = 0;
  jQuery(".category-item").each(function (event) {
    id = jQuery(this).data("termid");
  });
  getProductByCategory(id);
}
function getProductByCategory(id,i,a) {
 // spinnerON();
 if(a){

 }
 else{
  actualCat=i;
 }
 if(noCat==0)
 {
  jQuery(".loading").show(); 
  jQuery.ajax({
    type: "POST",
    url: wc_add_to_cart_params.ajax_url,
    data: {
      action: 'getProductsByCategoryId_kiosk',
      catid: id
    },
    success: function (data) {
      jQuery('#wc-products').html(data);
      jQuery( ".category-item" ).removeClass( "active" );
      output = `<h1>`+categories_kiosk[actualCat].name+`</h1>
      <hr>
<div class="card-deck">`;
    //  output = "<div class='container'><div class='col-md-12'><h1>"+categories_kiosk[actualCat].name+"</h1></div></div></br></br></br>";
    //  jQuery("<h1>"+categories_kiosk[actualCat].name+"</h1>").insertBefore("#card-deck");
      jQuery.each(data, function (key, value) {
        const arr = value.price.split(',');
        prices = "";
        jQuery.each(arr , function(k, v ){
          if(k === 0 ){
            prices += '<span>  $' + parseFloat(v).toFixed(2) + '</span>';
          }else if(k === arr.length-1){
            prices += '<span>  $' + parseFloat(v).toFixed(2) + '</span>';
          }
        });

        output += ` 
                        <div class="card"> 
                        <a href="#`+ value.ID + `" data-prodid="` + value.ID + `" class="modal-opener" onClick="openModal(this)">
                        <img class="card-img-top" src="`+ value.image + `" alt="Card image cap">
                        <div class="card-body">
                        <h5 class="card-title">`+ value.post_title + `</h5>
                        <div class="card-text">`+ prices + `</div>
                        </div>
                        </a>
                        </div>
              `;
        valueGlobal[value.ID] = value;
        //  getSingleProductComponents(value.ID); 
      });
      output += '</div>';
     if(a)
     {
      jQuery("#productsdiv").append(output);
     }
     else{
      actualCat=i;
      jQuery("#productsdiv").html(output);
     }
      /*  jQuery('#back_to_cat').click(function(){
         jQuery('#imgloading').show();
          getCategories();
        });*/

      console.log('done');

    },
    complete: function (data) {
    }
  }).done(function (data) {
    spinnerOFF();
    jQuery("[data-termid="+categories_kiosk[actualCat].term_id+"]").addClass("active");
    jQuery(".loading").hide();
    spinnerOFF();  
    if(actualCat<(categories_kiosk.length-1)){
      actualCat++;
    }
    else{
      actualCat=0;
    }
  });
 }
}
function create_modal(value, data) {
  const arr = value.price.split(',');
  prices = "";
  prices2 = "";
  jQuery.each(arr , function(k, v ){
    if(k === 0 ){
      prices += '<span>  $' + parseFloat(v).toFixed(2) + '</span>';
    }else if(k === arr.length-1){
      prices += '<span>  $' + parseFloat(v).toFixed(2) + '</span>';
    }
  });

  arr.forEach(element => prices2 += '' + element + '');
  output = '';
  output += `
       <button class="close-modal-button" onClick="closeModal()"> <i class="fa-solid fa-xmark"></i></button>
       <div class="modal-body-container">
         <div class="row modal-head">
           <div class="col-3">
             <img class="card-img-top" src="`+ value.image + `" alt="Card image cap">
           </div>
           <div class="col-9">
           <div class="header-title">
             <h3> `+ value.post_title + ` </h3>
           <div class="card-text"> 
          
           <h3 class='modal-prices'> `+ prices + ` </h3></div> </div>
             <p class="description">`+ data.description + `</p>
             <div style="display:none" class="alert alert-danger" role="alert">
             A simple danger alert—check it out!
           </div>
           <div class="serving-options-container">`+ add_serving_options(data.attributes_list, value, data) + `
           </div>
           <div class="row menu-info">
            <div class="costumer"> 
             Customer 
             <input  id="customerName" type="text"> 
            </div>
            <div class="Quantity">
               Quantity
               <div class="holder">
               <i class="fa-solid fa-circle-minus" data-prodid="` + value.ID + `" onClick="minusQuantity(this)"></i> <div class="quantity-number-` + value.ID + `" data-prodid ="` + value.ID + `" data-number="1">1</div>  <i class="fa-solid fa-circle-plus" data-prodid="`+ value.ID + `" onClick="addQuantity(this)"></i>      </div>
            </div>
         </div>
         </div>
         </div>
 
         <div class="component-main-container">
            
             `; if (data.components) {
              editvalue = false ;
              componentsArr = [];
              positionArr = [];
    output += add_components(data.components, value , componentsArr , positionArr , editvalue  );
    /* jQuery(".components-items-container").unbind('click').bind("DOMSubtreeModified", function() {
      console.log("list changed");
 
    }); */
  } else {
    jQuery('.modal').removeClass('bigger');
  }
  output += `
 
         </div>
       </div>
       <div class="modal-footer-container">
       <div class="modal-footer">
       <div data-price="`+ (arr.length === 1 ? prices2 : "0.00") + `" data-variation=0  data-prodid="` + value.ID + `" class="total-price-container total-` + value.ID + `">` + (arr.length === 1 ? prices : "$0.00") + `</div>
         <button type="button" class="btn btn-secondary add-to-cart-button" onclick="addItemToCart()">Add to Cart</button>
       </div>
     </div>
    `;
  jQuery('#main-modal').html(output);

  addComponentdefaultgetdata(data.components, value);
  detectServingOption(value.ID, data.attributes_list, data.variations);
}

function edit_modal_create(value, data , itemId , quantity , variations ,componentsItems) {
  const arr = value.price.split(',');
  prices = "";
  prices2 = "";
  varia = [];
  componentsArr = []; 
  positionArr = [];
  if(componentsItems){
    componentsItems.forEach(element => element != '' ? componentsArr[element.split('#')[0].split('_')[0]] = element.split('#')[1]  : "" );
    componentsItems.forEach(element => element != '' ? positionArr[element.split('#')[0].split('_')[0]] = element.split('#')[0].split('_')[1]  : "" );
  }
  console.log(componentsItems);
  console.log(componentsArr);
  console.log(positionArr);
  variations.forEach(element => element != '' ? varia[element] = element : "" );
  jQuery.each(arr , function(k, v ){
    if(k === 0 ){
      prices += '<span>  $' + parseFloat(v).toFixed(2) + '</span>';
    }else if(k === arr.length-1){
      prices += '<span>  $' + parseFloat(v).toFixed(2) + '</span>';
    }
  });
  arr.forEach(element => prices2 += '' + element + '');
  output = '';
  output += `
       <button class="close-modal-button" onClick="closeModal()"> <i class="fa-solid fa-xmark"></i></button>
       <div class="modal-body-container">
         <div class="row modal-head">
           <div class="col-3">
             <img class="card-img-top" src="`+ value.image + `" alt="Card image cap">
           </div>
           <div class="col-9">
           <div class="header-title">
             <h3> `+ value.post_title + ` </h3>
           <div class="card-text"> 
          
           <h3 class='modal-prices'> `+ prices + ` </h3></div> </div>
             <p class="description">`+ data.description + `</p>
             <div style="display:none" class="alert alert-danger" role="alert">
             A simple danger alert—check it out!
           </div>
           <div class="serving-options-container">`+ edit_serving_options(data.attributes_list, value, data , varia) + `
           </div>
           <div class="row menu-info">
            <div class="costumer"> 
             Customer 
             <input  id="customerName" type="text"> 
            </div>
            <div class="Quantity">
               Quantity
               <div class="holder">
               <i class="fa-solid fa-circle-minus" data-prodid="` + value.ID + `" onClick="minusQuantity(this)"></i> <div class="quantity-number-` + value.ID + `" data-prodid ="` + value.ID + `" data-number="`+quantity+`">`+quantity+`</div> <i class="fa-solid fa-circle-plus" data-prodid="`+ value.ID + `" onClick="addQuantity(this)"></i> </div>
            </div>
         </div>
         </div>
         </div>
 
         <div class="component-main-container">
            
             `; if (data.components) {
              editvalue = true;
    output += add_components(data.components, value ,componentsArr , positionArr , editvalue  );
    /* jQuery(".components-items-container").unbind('click').bind("DOMSubtreeModified", function() {
      console.log("list changed");
 
    }); */
  } else {
    jQuery('.modal').removeClass('bigger');
  }
  output += `
 
         </div>
 

       </div>
       <div class="modal-footer-container">
       <div class="modal-footer">
       <div data-price="`+ (arr.length === 1 ? prices2 : "0.00") + `" data-variation=0  data-prodid="` + value.ID + `" class="total-price-container total-` + value.ID + `">` + (arr.length === 1 ? prices : "$0.00") + `</div>
         <button type="button" class="btn btn-secondary add-to-cart-button" onclick="editItemToCart(this)" data-itemid="`+itemId+`">Save</button>
       </div>
     </div>
    `;
  jQuery('#main-modal').html(output);

  addeditedComponentdefaultgetdata(data.components, value , componentsArr , positionArr);
  detectServingOption(value.ID, data.attributes_list, data.variations);
}

function add_serving_options(data, productValue, infoId , varia) {
  variationArr = null ;
  variationArr = varia ;

  count++;

  if (data) {
    output = '';
    output += ` 
      <div class="serving-options-container">
      <form>
      `;
      
    jQuery.each(data, function (key, value) {
      replace_res = key.replace("attribute_pa_", "");
      title_variation = replace_res.replace(/-/g, " ");
          output += `
        <fieldset id="`+ key + `">
        <div class="serving-options-separator">
        <legend class="title" >`+ title_variation + `</legend> </div> <div  class="serving-options-separator" >`;
      jQuery.each(value, function (key_attr, attr) {
        if(attr != ""){
          output += ` <input type="radio" id="` + infoId.id + "-" + attr + `" name="` + key + `" data-variation="` + key + `" value="` + attr + `">
          <label for="`+ infoId.id + "-" + attr + `" name="` + key + `">` + attr + `</label><br>`;
        }
      });
      output += `
      </div>
        </fieldset>
  `;

    });
    output += `
      </form> 
      </div> `;

    return output;
  }
}
function edit_serving_options(data, productValue, infoId , varia) {
  variationArr = null ;
  variationArr = varia ;

  count++;
  if(varia ){
      console.log(varia.length);
      console.log(varia);
    
  }
  if (data) {
    output = '';
    output += ` 
      <div class="serving-options-container">
      <form>
      `;
    jQuery.each(data, function (key, value) {
      replace_res = key.replace("attribute_pa_", "");
      title_variation = replace_res.replace(/-/g, " ");
      output += `
        <fieldset id="`+ key + `">
        <div class="serving-options-separator">
        <legend class="title" >`+ title_variation + `</legend>  </div> <div class="serving-options-separator">`;
      jQuery.each(value, function (key_attr, attr) {
        if(varia[attr]){
          output += ` <input type="radio" id="` + infoId.id + "-" + attr + `" name="` + key + `" data-variation="` + key + `" value="` + attr + `" checked>
          <label for="`+ infoId.id + "-" + attr + `" name="` + key + `">` + attr + `</label><br>
`;
        }else{
          output += ` <input type="radio" id="` + infoId.id + "-" + attr + `" name="` + key + `" data-variation="` + key + `" value="` + attr + `">
          <label for="`+ infoId.id + "-" + attr + `" name="` + key + `">` + attr + `</label><br>
`;
        }

      });
      output += `
        </div>
        </fieldset>
  `;

    });
    output += `
      </form> 
      </div> `;

    return output;
  }
}

function add_components(data, value ,componentsArr , positionArr , editvalue ) {
  output = '';
  var componentCount = 0;
  console.log("DATA");
  console.log(data);
  console.log("DATA");
  jQuery.each(data, function (keyCategorydata, valueCategorydata) {
    
    componentCount++;
    output += `
        <div class="row components">
        <div class="col-5">
          <div id="`+ valueCategorydata.info.componentCatId +"-"+ value.ID + `" class="component-dropdown">
            <p  >` + keyCategorydata + `</p><p class="component-warning"> </p>
            `;
    output += `
          </div>
        </div>
        <div class="`+ "compo-" + valueCategorydata.info.componentCatId + "-" + value.ID + ` warning-container">
          <div class="components-items-container" id="`+ "compo-" + valueCategorydata.info.componentCatId + "-" + value.ID + `">
          `;
          jQuery.each(valueCategorydata.items, function (keyComponent, valueComponent) {

            /*               console.log(valueComponent.ruleId);
                          console.log(valueCategorydata.info.rules[valueComponent.ruleId]); 
                          console.log(keyCategorydata); */
            rulesGlobal[valueComponent.ruleId] = valueCategorydata.info.rules[valueComponent.ruleId];
            rules = valueCategorydata.info.rules;
            rulesId = valueComponent.ruleId;
            if(editvalue){
              if(componentsArr[valueComponent.componentId]){
                if (valueComponent.NumberOfPlacements === "1") {
                  rules = valueCategorydata.info.rules[valueComponent.ruleId];
                  console.log(rules);
                  output += `
                          <a  data-compname="`+ valueComponent.componentName + `" id="` + valueComponent.componentId + "-" +value.ID+ `" data-wcid="` + value.ID + `" `+(rules.MaxUnique === 1 ? 'data-unique = "true"' : "" )+` data-position='' data-action="" class=" active  component-item-`+valueCategorydata.info.componentCatId + `-` + value.ID +`" onclick="addItemComponent(this)" data-quantity="`+componentsArr[valueComponent.componentId]+`" data-price="` + (valueComponent.componentPrice ? valueComponent.componentPrice : 0) + `" data-category="` + keyCategorydata + `"data-componentid="`+ valueComponent.componentId +`"data-catid="` + valueCategorydata.info.componentCatId + `-` + value.ID + `" data-rule= "` + rulesId + `"  data-component= "` + valueComponent.componentName + `"   > <div class="col-12 item-main-container">` + valueComponent.componentName + " " + (valueComponent.componentPrice != 0 ? "$" + valueComponent.componentPrice : "") + `<div class="qty-controls-container">` +(componentsArr[valueComponent.componentId] > 0? rules.MaxUnique === 1 ? '<div class="quantity-controls"><i class="fa-solid fa-circle-check"></i> </div>' :  '<div class="quantity-controls"> <i class="fa-solid fa-circle-minus" data-componentid="' + valueComponent.componentId + "-" +value.ID+ '" onclick="removeitems(this)"></i> <div class="component-qty " > '+componentsArr[valueComponent.componentId]+'</div> <i class="fa-solid fa-circle-plus""></i> </div> ' : "" ) +  ` </div> </div></a>
                          `;
                } else {
                  output += `
                          <a  data-compname="`+ valueComponent.componentName + `" id="` + valueComponent.componentId + "-" +value.ID+ `" data-wcid="` + value.ID + `" `+(rules.MaxUnique === 1 ? 'data-unique = "true"' : "" )+` data-position='`+(!positionArr[valueComponent.componentId] ? "" : positionArr[valueComponent.componentId] )+`' data-action="" class="active component-item-`+valueCategorydata.info.componentCatId + `-` + value.ID +`" onclick="addItemComponent(this)" data-quantity="`+(valueComponent.isDefault ? valueComponent.isDefault : "0" )+`" data-price="` + (valueComponent.componentPrice ? valueComponent.componentPrice : 0) + `" data-category="` + keyCategorydata + `"data-componentid="`+ valueComponent.componentId +`"data-catid="` + valueCategorydata.info.componentCatId + `-` + value.ID + `" data-rule= "` + rulesId + `"  data-component= "` + valueComponent.componentName + `"   ><div class="col-12 item-main-container">` + valueComponent.componentName + " " + (valueComponent.componentPrice != 0 ? "$" + valueComponent.componentPrice : "") + `<div class="qty-controls-container">` +(componentsArr[valueComponent.componentId] > 0? rules.MaxUnique === 1 ? '<div class="quantity-controls"><i class="fa-solid fa-circle-check"></i> </div>' :  '<div class="quantity-controls"> <i class="fa-solid fa-circle-minus" data-componentid="' + valueComponent.componentId + "-" +value.ID+ '" onclick="removeitems(this)"></i> <div class="component-qty " > '+componentsArr[valueComponent.componentId]+' </div> <i class="fa-solid fa-circle-plus""></i> </div> ' : "" ) +  ' </div> </div> <div class="position-container col-12"> '+(componentsArr[valueComponent.componentId] > 0  ?  '<button class="position-selector '+(positionArr[valueComponent.componentId] === "left"? "active" : "")+'" data-componentid="' + valueComponent.componentId + "-" +value.ID+ '" onClick="selectposition(this)" data-position="left"> Left </button> <button class="position-selector '+( !positionArr[valueComponent.componentId] ? "active" : "")+'" data-componentid="' + valueComponent.componentId + "-" +value.ID+ '" onClick="selectposition(this)" data-position="whole"> Whole </button> <button class="position-selector '+(positionArr[valueComponent.componentId] === "right"? "active" : "")+'"  data-componentid="' + valueComponent.componentId + "-" +value.ID+ '" onClick="selectposition(this)" data-position="right"> Right </button>': "" )+' </div>  </a>';
    
                }
              }else{
                if (valueComponent.NumberOfPlacements === "1") {
                  rules = valueCategorydata.info.rules[valueComponent.ruleId];
                  console.log(rules);
                  output += `
                          <a  data-compname="`+ valueComponent.componentName + `" id="` + valueComponent.componentId + "-" +value.ID+ `" data-wcid="` + value.ID + `" `+(rules.MaxUnique === 1 ? 'data-unique = "true"' : "" )+` data-position='' data-action="" class="  component-item-`+valueCategorydata.info.componentCatId + `-` + value.ID +`" onclick="addItemComponent(this)" data-quantity="0" data-price="` + (valueComponent.componentPrice ? valueComponent.componentPrice : 0) + `" data-category="` + keyCategorydata + `"data-componentid="`+ valueComponent.componentId +`"data-catid="` + valueCategorydata.info.componentCatId + `-` + value.ID + `" data-rule= "` + rulesId + `"  data-component= "` + valueComponent.componentName + `"   > <div class="col-12 item-main-container">` + valueComponent.componentName + " " + (valueComponent.componentPrice != 0 ? "$" + valueComponent.componentPrice : "") + `<div class="qty-controls-container">` +(componentsArr[valueComponent.componentId] > 0? rules.MaxUnique === 1 ? '<div class="quantity-controls"><i class="fa-solid fa-circle-check"></i> </div>' :  '<div class="quantity-controls"> <i class="fa-solid fa-circle-minus" data-componentid="' + valueComponent.componentId + "-" +value.ID+ '" onclick="removeitems(this)"></i> <div class="component-qty " > '+componentsArr[valueComponent.componentId]+'</div> <i class="fa-solid fa-circle-plus""></i> </div> ' : "" ) +  ` </div> </div></a>
                          `;
                } else {
                  output += `
                          <a  data-compname="`+ valueComponent.componentName + `" id="` + valueComponent.componentId + "-" +value.ID+ `" data-wcid="` + value.ID + `" `+(rules.MaxUnique === 1 ? 'data-unique = "true"' : "" )+` data-position='`+positionArr[valueComponent.componentId]+`' data-action="" class=" component-item-`+valueCategorydata.info.componentCatId + `-` + value.ID +`" onclick="addItemComponent(this)" data-quantity="0" data-price="` + (valueComponent.componentPrice ? valueComponent.componentPrice : 0) + `" data-category="` + keyCategorydata + `"data-componentid="`+ valueComponent.componentId +`"data-catid="` + valueCategorydata.info.componentCatId + `-` + value.ID + `" data-rule= "` + rulesId + `"  data-component= "` + valueComponent.componentName + `"   ><div class="col-12 item-main-container">` + valueComponent.componentName + " " + (valueComponent.componentPrice != 0 ? "$" + valueComponent.componentPrice : "") + `<div class="qty-controls-container">` +(componentsArr[valueComponent.componentId] > 0? rules.MaxUnique === 1 ? '<div class="quantity-controls"><i class="fa-solid fa-circle-check"></i> </div>' :  '<div class="quantity-controls"> <i class="fa-solid fa-circle-minus" data-componentid="' + valueComponent.componentId + "-" +value.ID+ '" onclick="removeitems(this)"></i> <div class="component-qty " > '+componentsArr[valueComponent.componentId]+' </div> <i class="fa-solid fa-circle-plus""></i> </div> ' : "" ) +  ' </div> </div> <div class="position-container col-12"> '+(componentsArr[valueComponent.componentId] > 0  ?  '<button class="position-selector" data-componentid="' + valueComponent.componentId + "-" +value.ID+ '" onClick="selectposition(this)" data-position="left"> Left </button> <button class="position-selector active" data-componentid="' + valueComponent.componentId + "-" +value.ID+ '" onClick="selectposition(this)" data-position="whole"> Whole </button> <button class="position-selector" data-componentid="' + valueComponent.componentId + "-" +value.ID+ '" onClick="selectposition(this)" data-position="right"> Right </button>': "" )+' </div>  </a>';
                }
              }
            }else{
              if (valueComponent.NumberOfPlacements === "1") {
                rules = valueCategorydata.info.rules[valueComponent.ruleId];
                console.log(rules);
                output += `
                        <a  data-compname="`+ valueComponent.componentName + `" id="` + valueComponent.componentId + "-" +value.ID+ `" data-wcid="` + value.ID + `" `+(rules.MaxUnique === 1 ? 'data-unique = "true"' : "" )+` data-position='' data-action="" class="`+ (valueComponent.isDefault > 0? "active" : "" )+` component-item-`+valueCategorydata.info.componentCatId + `-` + value.ID +`" onclick="addItemComponent(this)" data-quantity="`+(valueComponent.isDefault ? valueComponent.isDefault : "0" )+`" data-price="` + (valueComponent.componentPrice ? valueComponent.componentPrice : 0) + `" data-category="` + keyCategorydata + `"data-componentid="`+ valueComponent.componentId +`"data-catid="` + valueCategorydata.info.componentCatId + `-` + value.ID + `" data-rule= "` + rulesId + `"  data-component= "` + valueComponent.componentName + `"   > <div class="col-12 item-main-container">` + valueComponent.componentName + " " + (valueComponent.componentPrice != 0 ? "$" + valueComponent.componentPrice : "") + `<div class="qty-controls-container">` +(valueComponent.isDefault > 0? rules.MaxUnique === 1 ? '<div class="quantity-controls"><i class="fa-solid fa-circle-check"></i> </div>' :  '<div class="quantity-controls"> <i class="fa-solid fa-circle-minus" data-componentid="' + valueComponent.componentId + "-" +value.ID+ '" onclick="removeitems(this)"></i> <div class="component-qty " > 1 </div> <i class="fa-solid fa-circle-plus""></i> </div> ' : "" ) +  ` </div> </div></a>
                        `;
              } else {
    /*             output += `
                        <li><a  class="has-component-submenu">`+ valueComponent.componentName + " " + (valueComponent.componentPrice != 0 ? "$" + valueComponent.componentPrice : "") + `</a>
                        <ul class="sub-menu">
                        <li data-position="left" data-catid="`+ valueCategorydata.info.componentCatId + `-` + value.ID + `" data-category="` + keyCategorydata + `" data-price="` + (valueComponent.componentPrice ? valueComponent.componentPrice : 0) + `" data-rule= "` + rulesId + `"  data-component= "` + valueComponent.componentName + `" onClick="addComponenttodisplay(this)" id="` + valueComponent.componentId + `" >left</li>
                        <li data-position="whole" data-catid="`+ valueCategorydata.info.componentCatId + `-` + value.ID + `" data-category="` + keyCategorydata + `" data-price="` + (valueComponent.componentPrice ? valueComponent.componentPrice : 0) + `" data-rule= "` + rulesId + `"  data-component= "` + valueComponent.componentName + `" onClick="addComponenttodisplay(this)" id="` + valueComponent.componentId + `" >whole</li>
                        <li data-position="right" data-catid="`+ valueCategorydata.info.componentCatId + `-` + value.ID + `" data-category="` + keyCategorydata + `" data-price="` + (valueComponent.componentPrice ? valueComponent.componentPrice : 0) + `" data-rule= "` + rulesId + `"  data-component= "` + valueComponent.componentName + `" onClick="addComponenttodisplay(this)" id="` + valueComponent.componentId + `" >right</li>
                        </ul>
                        </li>
                        ` */
                        output += `
                        <a  data-compname="`+ valueComponent.componentName + `" id="` + valueComponent.componentId + "-" +value.ID+ `" data-wcid="` + value.ID + `" `+(rules.MaxUnique === 1 ? 'data-unique = "true"' : "" )+` data-position='`+(valueComponent.isDefault > 0 ? "": "")+`' data-action="" class="`+ (valueComponent.isDefault > 0? "active" : "" )+` component-item-`+valueCategorydata.info.componentCatId + `-` + value.ID +`" onclick="addItemComponent(this)" data-quantity="`+(valueComponent.isDefault ? valueComponent.isDefault : "0" )+`" data-price="` + (valueComponent.componentPrice ? valueComponent.componentPrice : 0) + `" data-category="` + keyCategorydata + `"data-componentid="`+ valueComponent.componentId +`"data-catid="` + valueCategorydata.info.componentCatId + `-` + value.ID + `" data-rule= "` + rulesId + `"  data-component= "` + valueComponent.componentName + `"   ><div class="col-12 item-main-container">` + valueComponent.componentName + " " + (valueComponent.componentPrice != 0 ? "$" + valueComponent.componentPrice : "") + `<div class="qty-controls-container">` +(valueComponent.isDefault > 0? rules.MaxUnique === 1 ? '<div class="quantity-controls"><i class="fa-solid fa-circle-check"></i> </div>' :  '<div class="quantity-controls"> <i class="fa-solid fa-circle-minus" data-componentid="' + valueComponent.componentId + "-" +value.ID+ '" onclick="removeitems(this)"></i> <div class="component-qty " > 1 </div> <i class="fa-solid fa-circle-plus""></i> </div> ' : "" ) +  ' </div> </div> <div class="position-container col-12"> '+(valueComponent.isDefault > 0  ?  '<button class="position-selector" data-componentid="' + valueComponent.componentId + "-" +value.ID+ '" onClick="selectposition(this)" data-position="left"> Left </button> <button class="position-selector active" data-componentid="' + valueComponent.componentId + "-" +value.ID+ '" onClick="selectposition(this)" data-position="whole"> Whole </button> <button class="position-selector" data-componentid="' + valueComponent.componentId + "-" +value.ID+ '" onClick="selectposition(this)" data-position="right"> Right </button>': "" )+' </div>  </a>';
  
              }
            }
          });
    output +=`
          </div>
        </div>    
        </div> 
        `;
  });
  if (componentCount > 3) {
    jQuery('.modal').addClass('bigger');
  } else {
    jQuery('.modal').removeClass('bigger');
  }

  return output;



}


function addComponentdefaultgetdata(data, value) {
  console.log(data);
  console.log(value);
  jQuery.each(data, function (keyCategorydata, valueCategorydata) {
    jQuery.each(valueCategorydata.items, function (keyComponent, valueComponent) {
      if (valueComponent.isDefault) {
        addComponentdefault(valueComponent.NumberOfPlacements, valueCategorydata.info.componentCatId + "-" + value.ID, valueComponent, valueComponent.ruleId, valueCategorydata.info.rules, keyCategorydata, valueComponent.componentPrice, value.ID);
        checkMinimunRequired(valueCategorydata.info.componentCatId + "-" + value.ID, valueComponent, valueComponent.ruleId, valueCategorydata.info.rules, keyCategorydata);
      }
    });
  });
}
function addeditedComponentdefaultgetdata(data, value , componentsArr , positionArr) {

  jQuery.each(data, function (keyCategorydata, valueCategorydata) {
    jQuery.each(valueCategorydata.items, function (keyComponent, valueComponent) {
      if( componentsArr[valueComponent.componentId] ){
        addComponentdefault(valueComponent.NumberOfPlacements, valueCategorydata.info.componentCatId + "-" + value.ID, valueComponent, valueComponent.ruleId, valueCategorydata.info.rules, keyCategorydata, valueComponent.componentPrice, value.ID , componentsArr[valueComponent.componentId] , positionArr[valueComponent.componentId] );
        checkMinimunRequired(valueCategorydata.info.componentCatId + "-" + value.ID, valueComponent, valueComponent.ruleId, valueCategorydata.info.rules, keyCategorydata);
      }
    });
  });
}
/*
* Get Cart
 */
function getCart() {
  spinnerON();
  jQuery.ajax({
    type: "POST",
    url: wc_add_to_cart_params.ajax_url,
    data: {
      action: 'getCartItems_kiosk'
    },
    success: function (data) {
      $output = '';
      jQuery.each(data.items, function (key, value) {
        const arr = value.variation;
        $variations = '';
        $variationsData = '';
        jQuery.each(arr, function (key, value) {
          $variations += '<li>' + value + '</li>';
          $variationsData += value + " " ; 
        })
        componentsdata = '';
        if(value.product_component_id){
          Object.keys(value.product_component_id)?.forEach(element => componentsdata += element + "#" + Object.values(value.product_component_id)+ ",");
        }
        $output += `
          <div class="row cart-item">
          <div class="col-2 cart-icons"> <i class="fa-solid fa-pen-to-square" onclick="editmodal(this)" data-itemid="`+value.key+`" data-productid=`+value.product_id+` data-variationId=`+value.variation_id+` data-quantity="`+value.quantity+`" data-variationsdata="`+$variationsData+`" data-components="`+componentsdata+`"></i><i onclick="removeItemToCart('`+ value.key + `')" class="fa-solid fa-circle-xmark"></i></div>
          <div class="col-3 picture"><img src="`+ value.image + `"></div>
          <div class="col description"><h4>`+ value.name + `</h4><p>` + $variations + (value.product_component ? value.product_component : '') + `</p></div>
          <div class="col-1 total" >$`+ parseFloat(value.line_total).toFixed(2) + `</div>
          </div> 
          `
      })
      jQuery('.cart-box').html($output);
      jQuery('.cart-number').html(data.count);
      jQuery('#cart-subtotal').html("$" + parseFloat(data.subtotal).toFixed(2));
      jQuery('#cart-tax').html("$" + (data.tax).toFixed(2));
      jQuery('#cart-total').html("$" + data.total);
      jQuery('#item-price-global').html("$" + data.total);
    }
  }).done(function () {
    spinnerOFF();
  });
}

function openDropdown() {
  jQuery('.dropdown-content').toggleClass('show');
}

function addItemToCart() {

  var selCompo;
  selCompo = generatePayload(false);
  var selCompoQty = generatePayload(true);
  var priceTotal = jQuery(".total-price-container").data("price");
  var prodid = jQuery(".total-price-container").data("prodid");
  var variationid = jQuery(".total-price-container").data("variation");
  var qtystr = ".quantity-number-" + prodid;
  var qty = jQuery(qtystr).text();
  var price = parseFloat(priceTotal) + parseFloat(compoPrice);
  var customerName=jQuery("#customerName").val();
  console.log(selCompo);
  console.log(selCompoQty);
  spinnerON();
  jQuery.ajax({
    type: "POST",
    url: wc_add_to_cart_params.ajax_url,
    data: {
      action: 'woocommerce_ajax_add_to_cart',
      product_id: prodid,
      quantity: qty,
      variation_id: variationid,
      item_selected_for: customerName,
      product_price_input: parseFloat(priceTotal),
      selComponents: selCompo,
      selComponentsPrice: compoPrice,
      selComponentsQty: selCompoQty,

    },
    success: function (data) {
      getCart();
      compoPrice = 0;
    },
    error: function (request, status, error) {
      console.log('error: ' +request.responseText);
    }

  }).done(function () {
    spinnerOFF();
    getCart();
  });


}

function editItemToCart(e) {

  itemid = jQuery(e).data('itemid')
  removeItemToCart(itemid);
  var selCompo;
  selCompo = generatePayload(false);
  var selCompoQty = generatePayload(true);
  var priceTotal = jQuery(".total-price-container").data("price");
  var prodid = jQuery(".total-price-container").data("prodid");
  var variationid = jQuery(".total-price-container").data("variation");
  var qtystr = ".quantity-number-" + prodid;
  var qty = jQuery(qtystr).text();
  var price = parseFloat(priceTotal) + parseFloat(compoPrice);
  var customerName=jQuery("#customerName").val();
  spinnerON();
  jQuery.ajax({
    type: "POST",
    url: wc_add_to_cart_params.ajax_url,
    data: {
      action: 'woocommerce_ajax_add_to_cart',
      product_id: prodid,
      quantity: qty,
      variation_id: variationid,
      item_selected_for: customerName,
      product_price_input: priceTotal,
      selComponents: selCompo,
      selComponentsPrice: compoPrice,
      selComponentsQty: selCompoQty

    },
    success: function (data) {
      getCart();
      compoPrice = 0;
    },
    error: function (request, status, error) {
      console.log(request.responseText);
    }

  }).done(function () {
    spinnerOFF();
    getCart();
  });


}

function removeItemToCart(prodid) {
  jQuery.ajax({
    type: "POST",
    url: wc_add_to_cart_params.ajax_url,
    data: {
      action: 'woocommerce_remove_product_from_cart',
      product_id: prodid,
    },
    success: function (data) {
      getCart();
      
    }
  });
}




function addComponenttodisplay(e) {
  componentRule = jQuery(e).data('rule');
  rules = rulesGlobal[componentRule];
  text = jQuery(e).data('component');
  position = jQuery(e).data('position');
  category = jQuery(e).data('category');
  price = jQuery(e).data('price');
  id = jQuery(e).attr('id');
  catid = jQuery(e).data('catid');
  items = jQuery(".component-item-" + catid).length;
  wcId = jQuery(e).data('wcid');
  sameItem = jQuery("#" + id + "-" + wcId).length
  console.log(sameItem);
  if (items < rules.MaxAllowed) {
    if (sameItem == 0) {
      jQuery("#compo-" + catid).append('<a data-compname="' + text + '" id="' + id + '-' + wcId + '" class="component-item-' + catid + '" onClick="removefromdisplay(this)" ' + (position ? 'data-position="' + position + '"' : "data-position=''") + ' data-price="' + (price ? price : 0) + '"data-category="' + category + '" data-componentid = "' + id + '" data-catid="' + catid + '" data-quantity=1 data-rule="' + componentRule + '">' + text + " (1) " + (position ? position : " ") + (price != 0 ? " $" + price : "") + '<i class="fa-solid fa-xmark"></i></a>');
      selectedComp.push(id);
    }
    else {
      let alink = jQuery("#" + id + "-" + wcId);

      tmpQty = alink.data("quantity");
      tmpQty = tmpQty + 1
      alink.data("quantity", tmpQty);
      console.log("qty: " + tmpQty);
      alink.html(text + "(" + tmpQty + ") " + (position ? position : " ") + (price != 0 ? " $" + price * tmpQty : "") + "<i class=\"fa-solid fa-xmark\"></i>");
      selectedComp.push(id);
    }

  } else {
    jQuery(".alert-danger").html("just " + rules.MaxAllowed + " items allowed");
    jQuery(".alert-danger").show();
  }
  num = jQuery("#compo-" + catid + " a").length;
  if (rules.MinRequired != 0) {
    if (num < rules.MinRequired) {
      categoryerror = jQuery(".error-compo-" + catid).length;
      if (categoryerror === 0) {
        jQuery('<div class="error-compo-' + catid + '"> Please select at least ' + rules.MinRequired + ' ' + category + ' </div>').insertBefore("#compo-" + catid);
      }
    } else {
      jQuery('.error-compo-' + catid).remove();
    }
  }

}
function addComponentdefault(placement, datacat, datacompo, rule, rulesdata, category, price, productWcId, editquantity , currentPosition) {
  rule = rule;
  rules = rulesdata[rule];
  text = datacompo.componentName;
  id = datacompo.componentId;
  catid = datacat;
  wcId = productWcId;
  items2 = jQuery(".active.component-item-" + catid);
  totalItems = 0 
  jQuery.each(items2 , function (key , value ){
    totalItems += parseFloat(value.attributes['data-quantity'].value);
  })
  items = jQuery(".active.component-item-" + catid).length;
  if (totalItems < rules.MaxAllowed) {
/*     jQuery("#compo-" + catid).append('<a data-compname="' + text + '" id="' + id + '-' + productWcId + '" data-wcid="' + productWcId + '" class="component-item-' + catid + '" data-quantity="'+(editquantity  ? editquantity : 1)+'" onClick="removefromdisplay(this)" ' + (placement === "2" ? currentPosition ? 'data-position=' + currentPosition :  'data-position=' + '"whole"' : "data-position='' ") + ' data-price="' + (price ? price : 0) + '" data-category="' + category + '" data-componentid = "' + id + '" data-catid="' + datacat + '" data-rule="' + rule + '">' + text + " ("+(editquantity  ? editquantity : 1)+") " + (placement === "2" ? currentPosition ? currentPosition : "Whole" : " ") + (price != 0 ? " $" + price : "") + '<i class="fa-solid fa-xmark"></i></a>');
    selectedComp.push(id); */
  } else {
    jQuery("#"+catid + " .component-warning").html("(just " + rules.MaxAllowed + " items allowed)");
  }

}
function removefromdisplay(e) {
  componentRule = jQuery(e).data('rule');
  rules = rulesGlobal[componentRule];
  id = jQuery(e).attr('id');
  catid = jQuery(e).data('catid');
  qty = jQuery(e).data('quantity');
  category = jQuery(e).data('category');
  cpname = jQuery(e).data('compname');
  if (qty > 1) {
    qty = qty - 1
    jQuery(e).data('quantity', qty);
    jQuery(e).text(cpname + "(" + qty + ")");
  }

  else {
    text = jQuery(e).text();
  }


  num = jQuery("#compo-" + catid + " a").length;
  if (rules.MinRequired != 0) {
    if (num < rules.MinRequired) {
      categoryerror = jQuery(".error-compo-" + catid).length;
      if (categoryerror === 0) {
        jQuery('<div class="error-compo-' + catid + '"> Please select at least ' + rules.MinRequired + ' ' + category + ' </div>').insertBefore("#compo-" + catid);
      }

    } else {

    }
  }
}

function selectposition(e){
  componentId = jQuery(e).data('componentid');
  position = jQuery(e).data('position') === "whole" ? "" : jQuery(e).data('position') ;
  jQuery('#'+ componentId).data('position' , position );
  jQuery('#'+ componentId).attr('data-position', position);
  jQuery('#'+ componentId).data('action' , "position");
  jQuery('#'+ componentId).find('.position-container .position-selector').removeClass('active');
  jQuery(e).toggleClass('active');
}

function removeitems(e){
  componentId= jQuery(e).data('componentid');
  qtyData = jQuery('#'+ componentId).data('quantity');
  qty = qtyData - 1 ; 
  jQuery('#'+ componentId).attr('data-quantity', qty);
  jQuery('#'+ componentId).data('quantity' , qty);
  jQuery('#'+ componentId).data('action' , "subtract");
  jQuery('#'+ componentId).find('.component-qty').html(qty);
  console.log(qtyData);

  catid = jQuery('#'+ componentId).data('catid');
  items = jQuery(".active.component-item-" + catid);
  totalItems = 0 
  jQuery.each(items , function (key , value ){
    totalItems += parseFloat(value.attributes['data-quantity'].value);
  })
  componentRule = jQuery('#'+ componentId).data('rule');
  rules = rulesGlobal[componentRule];
  console.log(rules);
  if (totalItems < rules.MaxAllowed) {
    jQuery("#"+catid + " .component-warning").html("");
    jQuery(".compo-"+catid+".warning-container").removeClass('active');
  }
  if(rules.MinRequired){
    if(rules.MinRequired > totalItems){
      jQuery("#"+catid + " .component-warning").html("(Please Select at least " + rules.MinRequired + " item(s))");
      jQuery(".compo-"+catid+".warning-container").addClass('active');
    }

  }
  
}

function addItemComponent(e){
  
  componentRule = jQuery(e).data('rule');
  rules = rulesGlobal[componentRule];
  id = jQuery(e).attr('id');
  catid = jQuery(e).data('catid');
  qty = jQuery(e).data('quantity');
  category = jQuery(e).data('category');
  cpname = jQuery(e).data('compname');
  quantityN = jQuery(e).closest('.component-qty');
  action = jQuery(e).data('action');
  uniqueItem = jQuery(e).data('unique');

  items = jQuery(".active.component-item-" + catid);
  totalItems = 0 
  jQuery.each(items , function (key , value ){
    totalItems += parseFloat(value.attributes['data-quantity'].value);
  })

 if(uniqueItem === true ){
  if(qty === 0){
    if (totalItems < rules.MaxAllowed) {
      qty = qty + 1;
      jQuery(e).attr('data-quantity', qty);
      jQuery(e).data('quantity', qty);
      jQuery(e).addClass('active');
      controls = `<div class="quantity-controls"> <i class="fa-solid fa-circle-check"></i> </div>`
      jQuery(e).find('.item-main-container .qty-controls-container').html(controls);
    }else{
      jQuery("#"+catid + " .component-warning").html("(just " + rules.MaxAllowed + " items allowed)");
      jQuery(".compo-"+catid+".warning-container").addClass('active');
    }
    }else{
    qty = qty - 1;
    jQuery(e).removeClass('active');
    jQuery(e).find('.item-main-container .qty-controls-container').html("");
    jQuery(e).data('action', "");
    jQuery(e).attr('data-quantity', qty);
    jQuery(e).data('quantity', qty);

    totalItems = 0 
    jQuery.each(items , function (key , value ){
      totalItems += parseFloat(value.attributes['data-quantity'].value);
    })
  

    if (totalItems < rules.MaxAllowed) {
      jQuery("#"+catid + " .component-warning").html("");
      jQuery(".compo-"+catid+".warning-container").removeClass('active');
    }
    if(rules.MinRequired){
      if(rules.MinRequired > totalItems){
        jQuery("#"+catid + " .component-warning").html("(Please Select at least " + rules.MinRequired + " item(s))");
        jQuery(".compo-"+catid+".warning-container").addClass('active');
      }
  
    }
    
  }
 }else{
  if(qty === 0){
    if(action != "subtract" && action != "position"){
      if (totalItems < rules.MaxAllowed) {
        qty = qty + 1;
        jQuery(e).attr('data-quantity', qty);
        jQuery(e).data('quantity', qty);
        jQuery(e).addClass('active');
        controls = `<div class="quantity-controls"> <i class="fa-solid fa-circle-minus" data-componentid="`+id+`" onclick="removeitems(this)"></i> <div class="component-qty "> `+qty+` </div> <i class="fa-solid fa-circle-plus" "=""></i> </div>`;
        positionControls = '<button class="position-selector" data-componentid="' + id+ '" onClick="selectposition(this)" data-position="left"> Left </button> <button class="position-selector active" data-componentid="'+ id+ '" onClick="selectposition(this)" data-position="whole"> Whole </button> <button class="position-selector" data-componentid="' + id + '" onClick="selectposition(this)" data-position="right"> Right </button>';
        jQuery(e).find('.position-container').html(positionControls);
        jQuery(e).find('.item-main-container .qty-controls-container').html(controls);
      }else{
        jQuery("#"+catid + " .component-warning").html("(just " + rules.MaxAllowed + " items allowed)");
        jQuery(".compo-"+catid+".warning-container").addClass('active');
      }
    }else{
      jQuery(e).removeClass('active');
      jQuery(e).find('.item-main-container .qty-controls-container').html("");
      jQuery(e).find('.position-container').html("");
      jQuery(e).data('action', "");
    }
  }else if(qty => 1){
    if(action != "subtract" && action != "position"){
      if (totalItems < rules.MaxAllowed) {
        qty = qty + 1;
        jQuery(e).attr('data-quantity', qty);
        jQuery(e).data('quantity', qty);
        jQuery(e).find('.component-qty').html(qty);
      }else{
        jQuery("#"+catid + " .component-warning").html("(just " + rules.MaxAllowed + " items allowed)");
        jQuery(".compo-"+catid+".warning-container").addClass('active');
      }
    }else{
      jQuery(e).data('action', "");
    }  
  }
 }

  itemslast= jQuery(".active.component-item-" + catid);
  totalItemslast = 0 
  jQuery.each(itemslast , function (key , value ){
    totalItemslast += parseFloat(value.attributes['data-quantity'].value);
  })


  if (rules.MinRequired != 0) {
    if (totalItemslast > rules.MinRequired) {

      
    }else if(totalItemslast === rules.MinRequired){
      infoitem = jQuery("#"+catid + " .component-warning").text();
      if(infoitem === "(Please Select at least " + rules.MinRequired + " item(s))"){
        jQuery("#"+catid + " .component-warning").html("");
        jQuery(".compo-"+catid+".warning-container").removeClass('active');
      }
    }
  }


}

function checkMinimunRequired(datacat, datacompo, rule, rulesdata, category) {
  num = jQuery("#compo-" + datacat + " a").length;
  if (rulesdata[rule].MinRequired != 0) {
    if (num < rulesdata[rule].MinRequired) {
      jQuery('<div class="error-compo-' + datacat + '"> Please select at least ' + rulesdata[rule].MinRequired + ' ' + category + ' </div>').insertBefore("#compo-" + datacat);
    }
  }
}

function addQuantity(e) {
  id = jQuery(e).data('prodid');

  number = jQuery('.quantity-number-' + id).text();
  number++;
  jQuery('.quantity-number-' + id).html(number);
}

function minusQuantity(e) {
  id = jQuery(e).data('prodid');
  number = jQuery('.quantity-number-' + id).text();
  if (number != 0) {
    number--;
  }
  jQuery('.quantity-number-' + id).html(number);
}


function detectServingOption(Id, attributes, variations) {
  jQuery('fieldset').change(function () {
    conuntitems = 0;
    attributes_st = "";
    countTimes = 0;
    jQuery.each(attributes, function (key, value) {
      jQuery.each(value, function (key, attribs) {
        console.log("#" + Id + "-" + attribs);
        if (jQuery("#" + Id + "-" + attribs).is(':checked')) {
          console.log(attribs);
          conuntitems++;
          console.log(Id);
          console.log(conuntitems);
          attributes_st += attribs;
          jQuery.each(variations, function (keyvariations, vari) {
            if (Object.keys(vari.attributes).length === 2) {
            
              attributes_st_list = "";
              if (conuntitems === 2) {
                jQuery.each(vari.attributes, function (attkey, valueattr) {
                  attributes_st_list += valueattr;
                });
              }

              if (attributes_st_list) {
                if (attributes_st_list === attributes_st) {
                  console.log(attributes_st + "Id is: " + keyvariations);
                  console.log(vari);
                  jQuery('.total-' + Id).html("$" + vari.dispaly_price);
                  jQuery('.total-' + Id).attr("data-price", vari.dispaly_price);
                  jQuery('.total-' + Id).attr("data-variation", keyvariations);
                }
              }
            } else {
              datainf = jQuery("#" + Id + "-" + attribs).data('variation');
              if (vari.attributes[datainf] === attribs) {
                jQuery('.total-' + Id).html("$" + vari.dispaly_price);
                jQuery('.total-' + Id).attr("data-variation", keyvariations);
                jQuery('.total-' + Id).attr("data-price", vari.dispaly_price);
              }

            }
          });

        }
      });
    });
  });
}
function openModal(e) {
  id = jQuery(e).data('prodid');
  jQuery.ajax({
    type: "POST",
    url: wc_add_to_cart_params.ajax_url,
    data: {
      action: 'single_product_components_kiosk',
      product_id: id

    },
    success: function (data) {


      //  valueGlobal[value.ID] = value;
      dataGlobal[id] = data;
      /* create_modal(value, data); */
    }
  }).done(function () {
    create_modal(valueGlobal[id], dataGlobal[id]);
    jQuery('#modal-container').show();
  });
}
function editmodal(e){
  id = jQuery(e).data('productid');
  item = jQuery(e).data('itemid');
  quantity = jQuery(e).data('quantity');
  variationsData = jQuery(e).data('variationsdata');
  componentsitems = jQuery(e).data('components');
  componentsItemData = componentsitems.split(',') !="" ? componentsitems.split(',') : null ; 
  variations = variationsData.split(' ') ? variationsData.split(' ') : null;
  jQuery.ajax({
    type: "POST",
    url: wc_add_to_cart_params.ajax_url,
    data: {
      action: 'single_product_components_kiosk',
      product_id: id

    },
    success: function (data) {


      //  valueGlobal[value.ID] = value;
      dataGlobal[id] = data;
      /* create_modal(value, data); */
    }
  }).done(function () {
    edit_modal_create(valueGlobal[id], dataGlobal[id], item , quantity , variations , componentsItemData);
    jQuery('#modal-container').show();
  });
  console.log('edit');
}

function closeModal(e) {
  jQuery('#modal-container').hide();
}

function removeItemsfromCart() {
  jQuery.ajax({
    type: "POST",
    url: wc_add_to_cart_params.ajax_url,
    data: {
      action: 'remove_items_from_cart',
    },
    success: function (data) {
      if (data) {
        console.log('items removed');
      };
    }
  });
}


function restartCart(e, url) {
  window.location.href = url;
  removeItemsfromCart();
}

function spinnerON() {
  jQuery('#main-modal').html(`<div style="display:none" class="d-flex justify-content-center">
  <div class="spinner-border" style="width: 40rem; height: 40rem;color:white;" role="status">
    <span class="sr-only">Loading...</span>
  </div>
</div>`);
  jQuery('#modal-container').show();
  jQuery('#main-modal').css("background-color", "transparent");
  jQuery('#main-modal').css("box-shadow", "0 0");
}

function spinnerOFF() {
  jQuery("#modal-container").hide();
  jQuery('#main-modal').css("background-color", "white");
}

window.onclick = function (event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
  if (jQuery('#modal-container').is(":visible")) {
    if (!event.target.matches('#modal-container')) {
      jQuery('#modal-container').hide();
    }
  }
}
window.onclick = function (event) {
  var tar = jQuery(event.target);
  if (jQuery('#modal-container').is(":visible")) {
    if (event.target.matches('.blocker')) {
      jQuery('#modal-container').hide();
    }
  }
  if (event.target.matches('#myDropdown') || event.target.matches('.dropbtn') || event.target.matches('#item-price-global') || jQuery(tar).parents().is('#myDropdown') || jQuery(tar).parents().is('#cart-icon' || jQuery(tar).parents().is('.price'))) {
  } else {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
          openDropdown.classList.remove('show');
        }
      }
    }
  }

 
 function spinnerOFF()
 {
   jQuery("#modal-container").hide();
   jQuery('#main-modal').css("background-color","white");
 }

 function timeOutWarning()
 {
   jQuery('#main-modal').html(`<div style="display:none;color:white" class="d-flex justify-content-center">
   <div><h1>`+kiosk_vars_object.timeout_message+` &nbsp<h1></div>
  <div id="timeout-div"></div><div> <h1>&nbspseconds</h1></div>
 </div>
 <div class="d-grid gap-2">
  <button style="
  height: 100px;font-size:xx-large" onclick='moreTime()' class="btn btn-primary" type="button">I need more time</button>  
</div>
 `);
    jQuery('#modal-container').show();
    jQuery('#main-modal').css("background-color", "transparent");
    jQuery('#main-modal').css("box-shadow", "0 0");
  }

 function moreTime()
 {
  jQuery("#modal-container").hide();
  jQuery('#main-modal').css("background-color","white");
  timeout=kiosk_vars_object.timeout;
 }
 let $container = $('.card-deck');
var nextP=0;
var counter=1;
 function getAllProducts()
{ 
  if(nextP<counter)
  {
    jQuery(".loading").show();
    jQuery.ajax({
      type: "POST",
      url: wc_add_to_cart_params.ajax_url,
      data: {
        action: 'getAllProducts_kiosk',
        offset: nextP
      },
      success: function (data) {
        counter=data.count;
        console.log(data.lastCat);
        jQuery( ".category-item" ).removeClass( "active" );
        nextP=nextP+12;
      //  jQuery('#wc-products').html(data);
        output = "";
        jQuery.each(data.produtcs, function (key, value) {
          const arr = value.price.split(',');
          prices = "";
  
          arr.forEach(element => prices += '$' + parseFloat(element).toFixed(2) + '');
  
          output += ` 
                          <div class="card item" data-cat=`+value.cat_id+`> 
                          <a href="#`+ value.ID + `" data-prodid="` + value.ID + `" class="modal-opener" onClick="openModal(this)">
                          <img class="card-img-top" src="`+ value.image + `" alt="Card image cap">
                          <div class="card-body">
                          <h5 class="card-title">`+ value.post_title + `</h5>
                          <div class="card-text">`+ prices + `</div>
                          </div>
                          </a>
                          </div>
                `;
          valueGlobal[value.ID] = value;
          //  getSingleProductComponents(value.ID); 
        });
        output += '';
      
        jQuery(".card-deck").append(output);
        /*  jQuery('#back_to_cat').click(function(){
           jQuery('#imgloading').show();
            getCategories();
          });*/
  
  
      },
      complete: function (data) {
      }
    }).done(function (data) {
      jQuery("[data-termid="+data.lastCat+"]").addClass("active");
      jQuery(".loading").hide();
      spinnerOFF();  
    });
  }
 
}
  
  jQuery(document).ready(function(){


 const cardDeck=document.getElementById("productsdiv");
    cardDeck.addEventListener("scroll", function(event){
      const e = event.target;
      if (e.scrollHeight - e.scrollTop === e.clientHeight) {
      
      if(noCat==0)
      {
        getProductByCategory(categories_kiosk[actualCat].term_id,actualCat,true);
      }
     noCat=0;
      }
    });
  });
  jQuery('#guestBtn').click(function () {
   var guestPhone1= jQuery("#guestPhone").val();
    jQuery.ajax({
      type: "POST",
      url: wc_add_to_cart_params.ajax_url,
      data: {
        action: 'setGuestIdintifier',
        guestPhone: guestPhone1
  
      }
    }).done(function(){
      jQuery('#guestIdentifier').hide();  
      jQuery('#preorder').show();
      getCategories();
    });
   
  });