<?php 
      //http://localhost/notebook/wp-json/wp/posts/?_embed
  $wp_request_url = site_url().'/wp-json/wp/v2/blog?_embed';

  $wp_request_headers = array('Authorization' => 'Basic ' . base64_encode( 'mynotebook:mynotebook' ));
  // print_r($wp_request_headers);
  $body = array('title' => 'Lorem Ipsum ', 'content'=>'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.');

  $wp_posts = wp_remote_request(
    $wp_request_url,
    array(
        'method'    => 'GET',
        'headers'   => $wp_request_headers
        // 'body'      => $body
    )
  );
  // echo wp_remote_retrieve_response_code( $wp_posts ) . ' ' . 
  // wp_remote_retrieve_response_message( $wp_posts );

  $blogs = json_decode($wp_posts['body'], true);


// echo '<pre><br>';
// $blogs = $wp_blogs['body'];
// print_r($blogs);
// die();
?>

<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css" />
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h4>WP CRUD via Rest Api</h4> 
            <button type="submit" class="btn btn-success add-new" data-title="Edit" data-toggle="modal" data-target="#add"><span class="glyphicon glyphicon-plus-sign"></span> Add New</button>
            <div class="table-responsive">
                <table id="mytable" class="table table-bordred table-striped">
                    <thead>
                        <!-- <th><input type="checkbox" id="checkall" /></th> -->
                        <th>Image</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Edit</th>

                        <th>Delete</th>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($blogs as $blog) {
                          # code...

                          // echo '<pre><br>';
                          // print_r($blog);
                            
                        
                            ?>
                          <tr>
                            <!-- <td><input type="checkbox" class="checkthis" /></td> -->
                            <td> <img width='100' height='80' src="<?php echo $blog['_embedded']['wp:featuredmedia']['0']['source_url'];?>"></td>
                            <td><?php echo $blog['title']['rendered'];?></td>
                            <td><?php echo substr($blog['content']['rendered'],0,180);?></td>
                            <td><?php echo $blog['modified'];?></td>
                            <td><?php echo $blog['status'];?></td>
                            
                            <td>
                                <p data-placement="top" data-toggle="tooltip" title="Edit">
                                    <button data-id="<?php echo $blog['id'];?>" class="btn btn-primary btn-xs post-edit" data-title="Edit" data-toggle="modal" data-target="#edit"><span class="glyphicon glyphicon-pencil"></span></button>
                                </p>
                            </td>
                            <td>
                                <p data-placement="top" data-toggle="tooltip" title="Delete">
                                    <button  data-id="<?php echo $blog['id'];?>" class="btn btn-danger btn-xs post-delete" data-title="Delete" data-toggle="modal" data-target="#delete"><span class="glyphicon glyphicon-trash"></span></button>
                                </p>
                            </td>
                        </tr>

                        <?php } ?>
                        
                    </tbody>
                </table>

                <div class="clearfix"></div>
             <!--    <ul class="pagination pull-right">
                    <li class="disabled">
                        <a href="#"><span class="glyphicon glyphicon-chevron-left"></span></a>
                    </li>
                    <li class="active"><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li>
                        <a href="#"><span class="glyphicon glyphicon-chevron-right"></span></a>
                    </li>
                </ul> -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <form action="javascript:void(0)" id="formAdd">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                <h4 class="modal-title custom_align" id="Heading">Add Post Detail</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input id="title_edit" class="form-control" type="text" name="title" placeholder="Mohsin" />
                </div>
                <!-- <div class="form-group">
                    <input class="form-control" type="text" placeholder="Irshad" />
                </div> -->
                <div class="form-group">
                    <textarea id="description_edit" rows="4" class="form-control" name="content" placeholder="CB 106/107 Street # 11 Wah Cantt Islamabad Pakistan"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-lg" style="width: 100%;"><span class="glyphicon glyphicon-ok-sign"></span> Add</button>
            </div>
          </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <form action="javascript:void(0)" id="formEdit">
            <input type="hidden" id="post-eid" name="id"/>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                <h4 class="modal-title custom_align" id="Heading">Edit Your Detail</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input id="title_edit" class="form-control" type="text" name="title" placeholder="Mohsin" />
                </div>
                <!-- <div class="form-group">
                    <input class="form-control" type="text" placeholder="Irshad" />
                </div> -->
                <div class="form-group">
                    <textarea id="description_edit" rows="4" class="form-control" name="content" placeholder="CB 106/107 Street # 11 Wah Cantt Islamabad Pakistan"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning btn-lg" style="width: 100%;"><span class="glyphicon glyphicon-ok-sign"></span> Update</button>
            </div>
          </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="javascript:void(0)" id="formDelete">
            <input type="hidden" id="post-did" name="id"/>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                <h4 class="modal-title custom_align" id="Heading">Delete this entry</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Record?</div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok-sign"></span> Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> No</button>
            </div>
        </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#mytable #checkall").click(function () {
            if ($("#mytable #checkall").is(":checked")) {
                $("#mytable input[type=checkbox]").each(function () {
                    $(this).prop("checked", true);
                });
            } else {
                $("#mytable input[type=checkbox]").each(function () {
                    $(this).prop("checked", false);
                });
            }
        });

        $("[data-toggle=tooltip]").tooltip();
    });



    jQuery("#formAdd").on("submit", function () {
        var frmdata = jQuery(this).serialize();
        // var nonce_param = "controller=posts&method=update_post";
        // jQuery.post(ajaxurl , function (response) {
            // var nonce = response.nonce;
            frmdata += "&action=" + 'add_custom_posts';
            // console.log(frmdata);
            jQuery.post(ajaxurl, frmdata, function (response) {
                console.log(response);
                if(response=="Created"){
                    setTimeout(function(){
                        location.reload();
                    },1200);
                }
                else{
                    console.log("Something went wrong!");
                }
            });
        // });
    });



    jQuery(document).on("click", ".post-edit", function () {
        var title = jQuery(this).parents("tr").find("td:nth(1)").text();
        var description = jQuery(this).parents("tr").find("td:nth(2)").text();
        jQuery("#edit #title_edit").val(title);
        jQuery("#edit #description_edit").val(description);
        jQuery("#post-eid").val(jQuery(this).attr("data-id"));

    });    


    jQuery("#formEdit").on("submit", function () {
        var frmdata = jQuery(this).serialize();
        // var nonce_param = "controller=posts&method=update_post";
        // jQuery.post(ajaxurl , function (response) {
            // var nonce = response.nonce;
            frmdata += "&action=" + 'update_custom_posts';
            // console.log(frmdata);
            jQuery.post(ajaxurl, frmdata, function (response) {
              console.log(response);
                console.log(response);
                if(response=="OK"){
                    setTimeout(function(){
                        location.reload();
                    },1200);
                }
                else{
                    console.log("Something went wrong!");
                }
            });
        // });
    });


    jQuery(document).on("click", ".post-delete", function () {

        jQuery("#post-did").val(jQuery(this).attr("data-id"));

    });

    jQuery("#formDelete").on("submit", function () {
        var frmdata = jQuery(this).serialize();
        // var nonce_param = "controller=posts&method=update_post";
        // jQuery.post(ajaxurl , function (response) {
            // var nonce = response.nonce;
            frmdata += "&action=" + 'delete_custom_posts';
            // console.log(frmdata);
            jQuery.post(ajaxurl, frmdata, function (response) {
                console.log(response);
                if(response=="OK"){
                    setTimeout(function(){
                        location.reload();
                    },1200);
                }
                else{
                    console.log("Something went wrong!");
                }
              
            });
        // });
    });


</script>
