<div id="wrap">
    
<h2>Message Lists</h2>
<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Title</th>
                <th>Content</th>
                <th>Create At</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $messages = $wpdb->get_results("SELECT * FROM $table_name",ARRAY_A);
            
            foreach ($messages as $message) { ?>
                <tr>
                <td><?php  echo $message['id'] ?></td>
                <td><?php  echo $message['name'] ?></td>
                <td><?php  echo $message['email'] ?></td>
                <td><?php  echo $message['phone'] ?></td>
                <td><?php  echo $message['title'] ?></td>
                <td><?php  echo $message['content'] ?></td>
                <td><?php  echo $message['create_at'] ?></td>
            </tr>

                <?php 
                
            }

            ?>

            
            
        </tbody>
        <tfoot>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Title</th>
                <th>Content</th>
                <th>Create At</th>
            </tr>
        </tfoot>
    </table>
    </div>