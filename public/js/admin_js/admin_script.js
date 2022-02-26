// checking current password
$(document).ready(function(){
    //check admin password is correct or not
    $("#current_pwd").keyup(function(){
        var current_pwd = $("#current_pwd").val();
        // alert(current_pwd);
        $.ajax({
            type: 'post',  
            url: '/admin/check-current-pwd',
            data: {current_pwd:current_pwd}, 
            success:function(resp){
                if(resp == "false"){
                    $("#chkCurrentPwd").html("<font color=red>current password is incorrect.");
                }else if (resp == "true") {
                    $("#chkCurrentPwd").html("<font color=green>current password is correct.");
                }
            },
            error:function(){
                // alert("Error");
            }
        });
    });

     // update section status
    $(".updateSectionStatus").click(function(){
        var status = $(this).children('i').attr("status");
        var section_id = $(this).attr("section_id");
        $.ajax({
            type: 'post',
            url:'/admin/update-section-status',
            data:{status:status,section_id:section_id},
            success:function(resp){
                if(resp['status'] == 0){
                    $("#section-" + section_id).html("<i status='Inactive' aria-hidden='true' class='fas fa-toggle-off fa-lg'></i>");
                }else if(resp['status'] == 1){
                    $("#section-" + section_id).html("<i status='Active' aria-hidden='true' class='fas fa-toggle-on fa-lg'></i>");
                }
            },
            error:function(){
                alert('Error..')
            }
        })
    });

     // update Brands status
    $(".updateBrandStatus").click(function(){
        var status = $(this).children('i').attr("status");
        var brand_id = $(this).attr("brand_id");
        $.ajax({
            type: 'post',
            url:'/admin/update-brand-status',
            data:{status:status,brand_id:brand_id},
            success:function(resp){
                if(resp['status'] == 0){
                    $("#brand-" + brand_id).html("<i status='Inactive' aria-hidden='true' class='fas fa-toggle-off fa-lg'></i>");
                }else if(resp['status'] == 1){
                    $("#brand-" + brand_id).html("<i status='Active' aria-hidden='true' class='fas fa-toggle-on fa-lg'></i>");
                }
            },
            error:function(){
                alert('Error..')
            }
        })
    });
    
    // update categories status
    $(".updateCategoryStatus").click(function(){
        var status = $(this).children('i').attr("status");
        var category_id = $(this).attr("category_id");
        $.ajax({
            type: 'post',
            url:'/admin/update-category-status',
            data:{status:status,category_id:category_id},
            success:function(resp){
                if(resp['status'] == 0){
                    $("#category-" + category_id).html("<i status='Inactive' aria-hidden='true' class='fas fa-toggle-off fa-lg'></i>");
                }else if(resp['status'] == 1){
                    $("#category-" + category_id).html("<i status='Active' aria-hidden='true' class='fas fa-toggle-on fa-lg'></i>");
                }
            },
            error:function(){
                alert('Error..')
            }
        });
    });

    // Append categories level
    $('#section_id').change(function(){
        var section_id = $(this).val();
        // alert(section_id);
        $.ajax({
            type: 'post',
            url: '/admin/append-categories-level',
            data: {section_id:section_id},
            success:function(resp){
                $('#appendCategoriesLevel').html(resp);
            },
            error:function(){ 
                alert("Error");
            }
        });
    });

    // comfirewm Reletion of Record
    $(".confirmDelete").click(function(){
        var name = $(this).attr("name");
        if(confirm("Are you sure to delete this "+name+"?")){
            return true;
        }
        return false;
    });

    // update products status
    $(".updateProductStatus").click(function(){
        var status = $(this).children('i').attr("status");
        var product_id = $(this).attr("product_id");
        $.ajax({
            type: 'post',
            url:'/admin/update-product-status',
            data:{status:status,product_id:product_id},
            success:function(resp){
                if(resp['status'] == 0){
                    $("#product-" + product_id).html("<i status='Inactive' aria-hidden='true' class='fas fa-toggle-off fa-lg'></i>");
                }else if(resp['status'] == 1){
                    $("#product-" + product_id).html("<i status='Active' aria-hidden='true' class='fas fa-toggle-on fa-lg'></i>");
                }
            },
            error:function(){
                alert('Error..')
            }
        })
    });

    //Add Edit Products 

    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div><input type="text" name="size[]" placeholder="Size" style="width: 100px; margin-top:10px" required />&nbsp;<input type="text" name="sku[]" placeholder="SKU"  style="width: 100px; margin-top:10px" required />&nbsp;<input type="number" name="price[]" placeholder="Price"  style="width: 100px; margin-top:10px" required />&nbsp;<input type="number" name="stock[]" placeholder="Stock" style="width: 100px; margin-top:10px" required /><a href="javascript:void(0);" class="remove_button">Delete</a></div>'; //New input field html 
    var x = 1; //Initial field counter is 1
    
    //Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    });
    
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });

    // update Attribute status
    $(".updateAttributeStatus").click(function(){
    var status = $(this).children('i').attr("status");
        var attribute_id = $(this).attr("attribute_id");
        $.ajax({
            type: 'post',
            url:'/admin/update-attribute-status',
            data:{status:status,attribute_id:attribute_id},
            success:function(resp){
                if(resp['status'] == 0){
                    $("#attribute-" + attribute_id).html("<i status='Inactive' aria-hidden='true' class='fas fa-toggle-off fa-lg'></i>");
                }else if(resp['status'] == 1){
                    $("#attribute-" + attribute_id).html("<i status='Active' aria-hidden='true' class='fas fa-toggle-on fa-lg'></i>");
                }
            },
            error:function(){
                alert('Error..')
            }
        })
    });

    // update Image status
    $(".updateImageStatus").click(function(){
         var status = $(this).children('i').attr("status");
        var image_id = $(this).attr("image_id");
        $.ajax({
            type: 'post',
            url:'/admin/update-image-status',
            data:{status:status,image_id:image_id},
            success:function(resp){
                if(resp['status'] == 0){
                    $("#image-" + image_id).html("<i status='Inactive' aria-hidden='true' class='fas fa-toggle-off fa-lg'></i>");
                }else if(resp['status'] == 1){
                    $("#image-" + image_id).html("<i status='Active' aria-hidden='true' class='fas fa-toggle-on fa-lg'></i>");
                }
            },
            error:function(){
                alert('Error..')
            }
        })
    });

    // update Banner status
    $(".updateBannerStatus").click(function(){
        var status = $(this).children('i').attr("status");
        var banner_id = $(this).attr("banner_id");
        $.ajax({
            type: 'post',
            url:'/admin/update-banner-status',
            data:{status:status,banner_id:banner_id},
            success:function(resp){
                if(resp['status'] == 0){
                    $("#banner-" + banner_id).html("<i status='Inactive' aria-hidden='true' class='fas fa-toggle-off fa-lg'></i>");
                }else if(resp['status'] == 1){
                    $("#banner-" + banner_id).html("<i status='Active' aria-hidden='true' class='fas fa-toggle-on fa-lg'></i>");
                }
            },
            error:function(){
                alert('Error..')
            }
        })
    });

});