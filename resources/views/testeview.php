
<ul class="list-group mt-2">
                
        <?php foreach($courses as $course){?>
                    
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?php echo $course->name ?>
                <li>
                    <?php
                        echo "$course->id, $course->modality,"
                    ?>
                </li>
            </li>
                    
        <?php } ?>
                
</ul>