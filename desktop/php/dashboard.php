<?php
if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
if (init('object_id') == '') {
    $_GET['object_id'] = $_SESSION['user']->getOptions('defaultDashboardObject');
}
$object = object::byId(init('object_id'));
if (!is_object($object)) {
    $object = object::rootObject();
}
if (!is_object($object)) {
    throw new Exception('{{Aucun objet racine trouvé}}');
}
$child_object = object::buildTree($object);
?>

<div class="row row-overflow">
    <div class="col-md-2">
        <center>
            <?php
            if (init('category', 'all') == 'all') {
                echo '<a href="index.php?v=d&p=dashboard&object_id=' . init('object_id') . '&category=all" class="btn btn-primary btn-sm categoryAction" style="margin-bottom: 5px;margin-right: 3px;">{{Tous}}</a>';
            } else {
                echo '<a href="index.php?v=d&p=dashboard&object_id=' . init('object_id') . '&category=all" class="btn btn-default btn-sm categoryAction" style="margin-bottom: 5px;margin-right: 3px;">{{Tous}}</a>';
            }
            foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                if (init('category', 'all') == $key) {
                    echo '<a href="index.php?v=d&p=dashboard&object_id=' . init('object_id') . '&category=' . $key . '" class="btn btn-primary btn-sm categoryAction" data-l1key="' . $key . '" style="margin-bottom: 5px;margin-right: 3px;">{{' . $value['name'] . '}}</a>';
                } else {
                    echo '<a href="index.php?v=d&p=dashboard&object_id=' . init('object_id') . '&category=' . $key . '" class="btn btn-default btn-sm categoryAction" data-l1key="' . $key . '" style="margin-bottom: 5px;margin-right: 3px;">{{' . $value['name'] . '}}</a>';
                }
            }
            ?>
        </center>
        <div class="bs-sidebar">
            <ul id="ul_object" class="nav nav-list bs-sidenav">
                <li class="nav-header">{{Liste objets}} </li>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
                $allObject = object::buildTree();
                foreach ($allObject as $object_li) {
                    if ($object_li->getIsVisible() == 1) {
                        $margin = 15 * $object_li->parentNumber();
                        if ($object_li->getId() == $object->getId()) {
                            echo '<li class="cursor li_object active" ><a href="index.php?v=d&p=dashboard&object_id=' . $object_li->getId() . '&category=' . init('category', 'all') . '" style="position:relative;left:' . $margin . 'px;">' . $object_li->getDisplay('icon') . ' ' . $object_li->getName() . '</a></li>';
                        } else {
                            echo '<li class="cursor li_object" ><a href="index.php?v=d&p=dashboard&object_id=' . $object_li->getId() . '&category=' . init('category', 'all') . '" style="position:relative;left:' . $margin . 'px;">' . $object_li->getDisplay('icon') . ' ' . $object_li->getName() . '</a></li>';
                        }
                    }
                }
                ?>
            </ul>
        </div>
    </div>

    <div class="col-md-8" id="div_displayObject">
        <div style="height: 10px;width: 100%;"></div>
        <?php
        echo '<div object_id="' . $object->getId() . '">';
        echo '<legend>' . $object->getDisplay('icon') . ' ' . $object->getName() . '</legend>';
        echo '<div class="div_displayEquipement" style="width: 100%;">';
        foreach ($object->getEqLogic() as $eqLogic) {
            if ($eqLogic->getIsVisible() == '1' && (init('category', 'all') == 'all' || $eqLogic->getCategory(init('category')) == 1)) {
                echo $eqLogic->toHtml('dashboard');
            }
        }
        echo '</div>';
        foreach ($child_object as $child) {
            $margin = 40 * $child->parentNumber();
            echo '<div object_id="' . $child->getId() . '" style="margin-left : ' . $margin . 'px;">';
            echo '<legend>' . $child->getDisplay('icon') . ' ' . $child->getName() . '</legend>';
            echo '<div class="div_displayEquipement" id="div_ob' . $child->getId() . '" style="width: 100%;">';
            foreach ($child->getEqLogic() as $eqLogic) {
                if ($eqLogic->getIsVisible() == '1' && (init('category', 'all') == 'all' || $eqLogic->getCategory(init('category')) == 1)) {
                    echo $eqLogic->toHtml('dashboard');
                }
            }
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        ?>
    </div>
    <div class="col-md-2">
        <legend>{{Scénarios}}</legend>
        <?php
        if (init('object_id') == 'global') {
            foreach (scenario::byObjectId(null) as $scenario) {
                if ($scenario->getIsVisible() == 1) {
                    echo $scenario->toHtml('dashboard');
                }
            }
        }
        foreach ($object->getScenario(false) as $scenario) {
            if ($scenario->getIsVisible() == 1) {
                echo $scenario->toHtml('dashboard');
            }
        }
        foreach ($child_object as $child) {
            foreach ($child->getScenario(false) as $scenario) {
                if ($scenario->getIsVisible() == 1) {
                    echo $scenario->toHtml('dashboard');
                }
            }
        }
        ?>
    </div>     
</div>

<?php include_file('desktop', 'dashboard', 'js'); ?>