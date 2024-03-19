<div class="container">
  <?php global $wpdb;
  $shelf_table = $wpdb->prefix . 'owt_tbl_books_shelf';
  $book_shelf = $wpdb->get_results("SELECT id,shelf_name FROM $shelf_table",ARRAY_A);


   ?>
<div class="row">
  <div class="col-md-12 col-lg-8">
        <h4 class="mb-3">Create Book</h4>
        <form class="form-horizontal" id="create-book-form" method="post"  enctype="multipart/form-data">
          <div class="row g-3">

            <div class="col-md-8">
              <label for="book_shelf" class="form-label">SELECT BOOK SHELF</label>
              <select class="form-select" id="book_shelf" name="book_shelf" required>
                <option value="">Choose...</option>
                <?php foreach ($book_shelf as $shelf) { ?>
                  <option value="<?php echo $shelf['id'] ?>"><?php echo $shelf['shelf_name'] ?></option>

                  <?php 

                  
                } ?>
                
                
              </select>
              
            </div>


            <div class="col-sm-6">
              <label for="txt_name" class="form-label">Name</label>
              <input type="text" class="form-control" id="txt_name" placeholder="Enter Name" value="" required="" name="txt_name">
              
            </div>

            

            <div class="col-12">
              <label for="txt_email" class="form-label">Email</label>
              <div class="input-group">
                <span class="input-group-text">@</span>
                <input type="email" class="form-control" id="txt_email" name="txt_email" placeholder="Email" required="">
              
              </div>
            </div>

            

            <div class="col-12">
              <label for="txt_publication" class="form-label">Publication</label>
              <input type="text" class="form-control" id="txt_publication" placeholder="Publication" name="txt_publication">
              
            </div>

            <div class="col-12">
              <label for="txt_description" class="form-label">Description </label>
              <textarea class="form-control" name="txt_description" id="txt_description"></textarea>
            </div>

            <div class="col-12">
              <label for="txt_image" class="form-label h3">Book Image</label>
              <input type="file" class="form-control" id="txt_image" name="txt_image">

              <img class="mt-3" width=96 height=96 id="book-image-preview" src="" alt="Book image preview">
              
            </div>


            <div class="col-12">
              <label for="txt_cost" class="form-label">Book Cost</label>
              <input type="number" min="1" class="form-control" id="txt_cost"  name="txt_cost" placeholder="Enter book cost">
              
            </div>



           

            <div class="col-md-4">
              <label for="txt_status" class="form-label">Status</label>
              <select class="form-select" id="txt_status" name="txt_status">
                <option value="">Choose...</option>
                <option value="1">Active</option>
                <option value="0">InActive</option>
              </select>
              
            </div>

          
          </div>

        

          <hr class="my-4">

          <hr class="my-4">

          <button id="create-book-form-btn" class="w-100 btn btn-primary btn-lg" type="submit">Submit</button>
        </form>
      </div>
</div>
</div>
