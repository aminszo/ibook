<div class="container wide">
    <div class="content wide">

        <div class="dashboard-header">
            <button class="button-1 small left" id="add-category">
                <span>افزودن دسته</span><i class="fas fa-folder-plus"></i>
            </button>
        </div>
        <div class="section-content wide">

            <div class ="column right" id="right-column">
                <?php printGroups($bookmarks, '1'); ?>
            </div> <!-- end of column right -->

            <div class ="column left" id="left-column">
                <?php printGroups($bookmarks, '2'); ?>
            </div> <!-- end of column left -->

        </div>
    </div>

    <div class="modal" id="add-category-modal">
        <div class="modal-box">
            <div class="box-content">
                <div class='fieldbox'>
                    <input type ='text' name ='category-title' id ='category-title' placeholder ='نام دسته بندی' class='ifield'>
                    <label for='category-title'><i class='far fa-folder-open'></i></label>  
                </div>
                <div class="buttons-container labeled" id="buttons-container">
                    <span>ستون : </span>
                    <div class="radio-button">
                        <div class="button-box">
                            <input type="radio" name="side" id="side-1" class="iradio" value="1" checked="checked">
                            <label for="side-1" class="radio-tube"><span class="radio-pan"></span></label>
                        </div>
                        <label for="side-1" class="radio-name">راست</label>
                    </div>
                    <div class="radio-button">
                        <div class="button-box">
                            <input type="radio" name="side" id="side-2" class="iradio" value="2">
                            <label for="side-2" class="radio-tube"><span class="radio-pan"></span></label>
                        </div>
                        <label for="side-2" class="radio-name">چپ</label>
                    </div>
                    <label class="the-label"><i class='far fa-columns'></i></label> 
                </div>
                <div class="color-select-box labeled">
                    <span>رنگ : </span>
                    <span class="color-indicator"></span>
                    <div class="color-selector">
                        <input type="radio" name="color" id="color-1" class="color-i" value="1" >
                        <label for="color-1" class="i-color one _1"></label>
                        <input type="radio" name="color" id="color-2" class="color-i" value="2">
                        <label for="color-2" class="i-color one _2"></label>
                        <input type="radio" name="color" id="color-3" class="color-i" value="3">
                        <label for="color-3" class="i-color one _3"></label>
                        <input type="radio" name="color" id="color-4" class="color-i" value="4">
                        <label for="color-4" class="i-color one _4"></label>
                        <input type="radio" name="color" id="color-5" class="color-i" value="5">
                        <label for="color-5" class="i-color one _5"></label>
                        <input type="radio" name="color" id="color-6" class="color-i" value="6" checked="checked">
                        <label for="color-6" class="i-color one _6"></label>
                        <input type="radio" name="color" id="color-7" class="color-i" value="7">
                        <label for="color-7" class="i-color one _7"></label>
                        <input type="radio" name="color" id="color-8" class="color-i" value="8">
                        <label for="color-8" class="i-color one _8"></label>
                        <input type="radio" name="color" id="color-9" class="color-i" value="9">
                        <label for="color-9" class="i-color one _9"></label>
                    </div>
                    <label class="the-label" ><i class='fas fa-fill-drip'></i></label>
                </div>
            </div>
            <div class="box-footer">
                <button class="button-3 accept disabled" id="add-category-accept"><span>تایید</span></button>
                <button class="button-3 cancel"><span>لغو</span></button>
            </div>
        </div>
    </div>

    <div class="modal" id="edit-category-modal">
        <div class="modal-box">
            <div class="box-content">
                <div class='fieldbox'>
                    <input type ='text' name ='category-title-edit' id ='category-title-edit' placeholder ='نام دسته بندی' class='ifield'>
                    <label for='category-title-edit'><i class='far fa-folder-open'></i></label>  
                </div>
                <div class="color-select-box labeled" id="category-edit-csb">
                    <span>رنگ : </span>
                    <span class="color-indicator"></span>
                    <div class="color-selector">
                        <input type="radio" name="color-e" id="color-e-1" class="color-i white" value="1" >
                        <label for="color-e-1" class="i-color two _1"></label>
                        <input type="radio" name="color-e" id="color-e-2" class="color-i black" value="2">
                        <label for="color-e-2" class="i-color two _2"></label>
                        <input type="radio" name="color-e" id="color-e-3" class="color-i blue" value="3">
                        <label for="color-e-3" class="i-color two _3"></label>
                        <input type="radio" name="color-e" id="color-e-4" class="color-i red" value="4">
                        <label for="color-e-4" class="i-color two _4"></label>
                        <input type="radio" name="color-e" id="color-e-5" class="color-i orange" value="5">
                        <label for="color-e-5" class="i-color two _5"></label>
                        <input type="radio" name="color-e" id="color-e-6" class="color-i green" value="6">
                        <label for="color-e-6" class="i-color two _6"></label>
                        <input type="radio" name="color-e" id="color-e-7" class="color-i pink" value="7">
                        <label for="color-e-7" class="i-color two _7"></label>
                        <input type="radio" name="color-e" id="color-e-8" class="color-i yellow" value="8">
                        <label for="color-e-8" class="i-color two _8"></label>
                        <input type="radio" name="color-e" id="color-e-9" class="color-i purple" value="9">
                        <label for="color-e-9" class="i-color two _9"></label>
                    </div>
                    <label class="the-label" ><i class='fas fa-fill-drip'></i></label>
                </div>
            </div>
            <div class="box-footer">
                <button class="button-3 accept disabled" id="edit-category-accept"><span>تایید</span></button>
                <button class="button-3 cancel"><span>لغو</span></button>
            </div>
        </div>
    </div>

    <div class="modal" id="delete-category-modal">
        <div class="modal-box">
            <div class="box-content">
                <p class="message">
                    دسته بندی "
                    <span></span>
                    " حذف شود؟
                </p>
                <p class="sub message">
                    بوکمارک های این دسته بندی را چه میکنید؟
                </p>
                <div class="buttons-container labeled" id="buttons-container-2">
                    <div class="radio-button">
                        <div class="button-box">
                            <input type="radio" name="bookmarks-decide" id="bookmarks-decide-delete" class="iradio" value="1" checked="checked">
                            <label for="bookmarks-decide-delete" class="radio-tube"><span class="radio-pan"></span></label>
                        </div>
                        <label for="bookmarks-decide-delete" class="radio-name">حذف همه</label>
                    </div>
                    <div class="radio-button">
                        <div class="button-box">
                            <input type="radio" name="bookmarks-decide" id="bookmarks-decide-move" class="iradio" value="2">
                            <label for="bookmarks-decide-move" class="radio-tube"><span class="radio-pan"></span></label>
                        </div>
                        <label for="bookmarks-decide-move" class="radio-name">انتقال</label>
                    </div>
                    <label class="the-label"><i class='far fa-folder-times'></i></label> 
                </div>
                <div class="fieldbox" id="box_move_to">
                    <select name="move_to" id="move_to" class="ifield">
                        <option value="0" >انتقال به دسته...</option>
                    </select>
                    <label for="move_to"><i class="fas fa-list-ul"></i></label>
                </div>
            </div>
            <div class="box-footer">
                <button class="button-3 accept" id="delete-category-accept"><span>تایید</span></button>
                <button class="button-3 cancel"><span>لغو</span></button>
            </div>
        </div>
    </div>

    <div class="modal" id="add-bookmark-modal">
        <div class="modal-box">
            <div class="box-content">
                <div class='fieldbox'>
                    <input type ='text' name ='bookmark-link' id ='bookmark-link' placeholder ='لینک بوکمارک' class='ifield eng'>
                    <label for='bookmark-link'><i class='far fa-link'></i></label>  
                </div>
                <div class='fieldbox'>
                    <input type ='text' name ='bookmark-title' id ='bookmark-title' placeholder ='نام بوکمارک' class='ifield'>
                    <label for='bookmark-title'><i class='fas fa-pennant'></i></label>  
                </div>
            </div>
            <div class="box-footer">
                <button class="button-3 accept disabled" id="add-bookmark-accept"><span>تایید</span></button>
                <button class="button-3 cancel"><span>لغو</span></button>
            </div>
        </div>
    </div>

    <div class="modal" id="edit-bookmark-modal">
        <div class="modal-box">
            <div class="box-content">
                <div class='fieldbox'>
                    <input type ='text' name ='bookmark-link-edit' id ='bookmark-link-edit' placeholder ='لینک بوکمارک' class='ifield eng'>
                    <label for='bookmark-link-edit'><i class='far fa-link'></i></label>  
                </div>
                <div class='fieldbox'>
                    <input type ='text' name ='bookmark-title-edit' id ='bookmark-title-edit' placeholder ='نام بوکمارک' class='ifield'>
                    <label for='bookmark-title-edit'><i class='fas fa-pennant'></i></label>  
                </div>
            </div>
            <div class="box-footer">
                <button class="button-3 accept disabled" id="edit-bookmark-accept"><span>تایید</span></button>
                <button class="button-3 cancel"><span>لغو</span></button>
            </div>
        </div>
    </div>

    <div class="modal" id="delete-bookmark-modal">
        <div class="modal-box">
            <div class="box-content">
                <p class="message">
                    بوکمارک "
                    <span></span>
                    " حذف شود؟
                </p>
            </div>
            <div class="box-footer">
                <button class="button-3 accept" id="delete-bookmark-accept"><span>تایید</span></button>
                <button class="button-3 cancel"><span>لغو</span></button>
            </div>
        </div>
    </div>

    <?php

    function printGroups($bookmarks, $side) {
        if (isset($bookmarks) && !empty($bookmarks)) {
            foreach ($bookmarks as $item) {
                if ($item['side'] === $side) {
                    ($item['side'] === '1') ? $side_ = 'right' : $side_ = 'left';
                    ?>
                    <div class="group <?php echo $side_; ?>" category_id="<?php echo $item['id']; ?>">
                        <div class="group-header" color_name="<?php echo $item['color'] ?>">
                            <div class="head-box"><i class="far fa-folder"></i><span><?php echo $item['name']; ?></span>
                                <button class="show-options"></button>
                                <div class="action-buttons">
                                    <button class="act-button delete"></button>
                                    <button class="act-button edit"></button>
                                    <button class="act-button moveup"></button>
                                    <button class="act-button movedown"></button>
                                    <button class="act-button moveleft"></button>
                                    <button class="act-button moveright"></button>
                                </div>
                            </div>
                        </div>
                        <div class="group-content">
                            <?php
                            if (isset($item['links'])) {
                                foreach ($item['links'] as $link) {
                                    echo '<div class="link-item" link-id="' . $link['id'] . '">';
                                    echo '<img src="' . base_url() . 'assets/image/sample-favicon.png" alt="favicon" class="favicon-pic"><a class="anchor" target="_blank" href="' . $link['link'] . '">' . $link['title'] . '</a>' . "\n";
                                    ?>
                                    <button class="show-options"></button>
                                    <div class="action-buttons">
                                        <button class="act-button delete"></button>
                                        <button class="act-button edit"></button>
                                        <button class="act-button moveup"></button>
                                        <button class="act-button movedown"></button>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="group_footer">
                        <button class="button-1 small add-bookmark">
                            <span>افزودن بوکمارک</span><i class="far fa-plus-octagon"></i>
                        </button>
                    </div>
                </div>
                <?php
            }
        }
    }
}
