<div class="section sidebarWithAccordion setWhite" data-id="<?= $wid; ?>">
    <div class="c">
        <div class="sidebarWithAccordionWrap lightBlueBg">
            <?php
                if(get_sub_field('sidebar_with_accordion_type') == "accordion"){
            ?>
                <img class="sidebarWithAccordionShadow" src="<?= get_bloginfo('template_directory'); ?>/assets/images/shadowBg.png" />
            <?php
                }else{
            ?>
                <img class="sidebarWithAccordionShadow" src="<?= get_bloginfo('template_directory'); ?>/assets/images/shadowBg2.png" />
            <?php
                }
            ?>
            <div class="sidebarWithAccordionHeader">
                <?php
                    $titleTag = get_sub_field('sidebar_with_accordion_title_tag');
                    $titleAlign = get_sub_field('sidebar_with_accordion_title_alignment');
                    echo "<$titleTag class='size45 fontWeight400 primaryBlack sidebarWithAccordionHeaderTitle $titleAlign'>" . get_sub_field('sidebar_with_accordion_title') . "</$titleTag>";
                    $sideBarTitle = get_sub_field('sidebar_with_accordion_sidebar_title');
                    $type = get_sub_field('sidebar_with_accordion_type');
                ?>
            </div>
            <div class="sidebarWithAccordionBody">
                <div class="sidebarWithAccordionBodyWrap sideBarYes" id="sideBarYes">
                    <div class="sideBar" id="sideBar">
                        <div class="textConntentHeader">
                            <p class="size28 lightBlack">Space Helper</p>
                        </div>
                        <div class="sideBarWrap whiteBg" id="sideBarWrap">
                            <div class="sideBarLinkList sideBarItem sidebarWithAccordionSideBarLinkList">
                                <div class="sideBarLinkListWrap">
                                    <p data-title="" class="sideBarPlaceholder size16 mobileSideBarPlaceholder"><span class="mobileSideBarPlaceholderLabel"><?= $sideBarTitle; ?> </span><span class="mobileSideBarPlaceholderLabelValue">Title</span></p>
                                    <ul class="sideBarLinkListChild">
                                        <li class="sideBarLinkListItem desktopSideBarPlaceholder"><span class="size22 fontWeight400 sideBarTiltle black"><?= $sideBarTitle; ?></span></li>
                                        <?php
                                            $sideBarItem = get_sub_field('sidebar_with_accordion_blocks');
                                            $sec = 0.2;
                                            foreach($sideBarItem as $k=>$item){
                                                $count = "";
                                                if($type == "accordion"){
                                                    $count = count($item['sidebar_with_accordion_blocks_accordion_blocks']);
                                                }else{
                                                    $count = "";
                                                }
                                        ?>
                                            <li class="sideBarLinkListItem <?= ($k == 0) ? 'sideBarLinkListItemActive' : ''; ?> <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay="<?= $sec; ?>s">
                                                <a class="size16 fontWeight500 black" data-id="<?= $k; ?>"><span class="sideBarLinkListItemLabel" data-title="<?= $item['sidebar_with_accordion_blocks_sidebar_title']; ?>"><?= $item['sidebar_with_accordion_blocks_sidebar_title']; ?></span><span class="sideBarLinkListItemCount"><?= $count; ?></span></a>
                                            </li>
                                        <?php
                                                $sec = $sec + 0.2;
                                            }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="textConntent">
                        <?php
                            $sideBarItem = get_sub_field('sidebar_with_accordion_blocks');
                            $psec = 0.2;
                            foreach($sideBarItem as $k=>$item){
                        ?>
                        <div class="textConntentWrap faqWrap faqWrap<?= $k; ?> <?= $type; ?>" id="<?= $k; ?>">
                            <div class="textConntentHeader  wow animate__fadeInUpSmall" data-wow-delay="0.2s">
                                <p class="size28 lightBlack"><?= $item['sidebar_with_accordion_blocks_title']; ?></p>
                            </div>
                            <div class="textConntentBody" id="textConntentBody<?= $k; ?>">
                                <?php
                                    if($type == "accordion"){
                                        $sec = 0.2;
                                        foreach($item['sidebar_with_accordion_blocks_accordion_blocks'] as $j=>$accordionItem){
                                ?>
                                        <div class="accordionItem whiteBg <?= WOW_ANIMATION_CLASS_FADEINUP_SMALL ?>" data-wow-delay="<?= $sec; ?>s">
                                            <div class="accordionTitle">
                                                <div class="accordionTitleLabel">
                                                    <p class="size18 primaryBlue fontWeight700"><?= $accordionItem['sidebar_with_accordion_blocks_accordion_blocks_title']; ?></p>
                                                </div>
                                                <div class="accordionPlusIconWrap"><span class="accordionPlusIcon"></span></div>
                                            </div>
                                            <div class="accordionText">
                                                <div class="cEditor">
                                                   <?= $accordionItem['sidebar_with_accordion_blocks_accordion_blocks_text']; ?>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                            $sec = $sec + 0.2;
                                        }
                                    }else{
                                ?>
                                        <div class="textContent  wow animate__fadeInUpSmall" data-wow-delay="<?= $psec; ?>s">
                                            <div class="cEditor">
                                                <?= $item['sidebar_with_accordion_blocks_text'] ?>
                                            </div>
                                        </div>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <?php
                                $psec = $psec + 0.2;
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>