<div class="container">
<div class="row">
  <div class="col-md-12 col-lg-8">
        <h4 class="mb-3">创建新书架
          
        </h4>
        <form class="form-horizontal" id="form-add-book-shelf">
          <div class="row g-3">
            <div class="col-sm-6">
              <label for="shelf_name" class="form-label">Shelf Name</label>
              <input type="text" class="form-control" id="shelf_name" placeholder="Enter Shelf Name" value="" required name="shelf_name">
              
            </div>

            

            <div class="col-12">
              <label for="capacity" class="form-label">Capacity</label>
              <div class="input-group">
                
                <input type="number" class="form-control" name="capacity" id="capacity" placeholder="Capacity" required>
              
              </div>
            </div>

            

            <div class="col-12">
              <label for="shelf_location" class="form-label">Shelf location</label>
              <input type="text" class="form-control" id="shelf_location" placeholder="Shelf location" name="shelf_location" required>
              
            </div>
           

            <div class="col-md-4">
              <label for="shelf_status" class="form-label">Status</label>
              <select class="form-select" id="shelf_status" name="shelf_status" required>
                <option value="">Choose...</option>
                <option value="1">1</option>
                <option value="0">0</option>
              </select>
              
            </div>

          
          </div>

        

          <hr class="my-4">

          <hr class="my-4">

          <button id="book-shelf-btn" class="w-100 btn btn-primary btn-lg" type="submit">Submit</button>
        </form>
      </div>
</div>
</div>
