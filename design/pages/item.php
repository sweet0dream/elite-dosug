<?php
    if(isset($_SESSION['auth'])) {
        if (isset($route[2]) && $route[2] == 'add' || $route[2] == 'edit') {

            //select for edit
            if ($route[2] == 'edit' && isset($route[3]) && !isset($value)) {
                if ($_SESSION['auth']['type'] == 'adm' && isset($_GET['user_id'])) {
                    $userId = $_GET['user_id'];
                } else {
                    $userId = $_SESSION['auth']['id'];
                }
                $value = item_decode(item_one($route[3], $userId));
                if ($userId != $value['user_id']) {
                    echo redirect($site['url'].'/user/');
                }
            } elseif($route[2] == 'add') {
                $userId = $_SESSION['auth']['id'];
            }
?>
<?php
            if($route[2] == 'add') {
?>
<div class="row justify-content-md-center g-0 mt-1">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card">
            <div class="card-header text-center"><b>Тип анкеты</b></div>
            <div class="card-body p-1">
                <div class="row g-1">
                    <?php foreach($types as $k => $v) { ?>
                        <div class="col-12 col-md-3">
                            <a href="?type=<?= $k ?>" class="btn btn-<?= isset($_GET['type']) && $_GET['type'] == $k ? 'secondary' : 'outline-secondary' ?> w-100">
                                <?= $v['names'][0] ?>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
            }
            if(isset($_GET['type']) && isset($types[$_GET['type']]) || $route[2] == 'edit') {
                if($route[2] == 'add') {
                    $type = $_GET['type'];
                } elseif($route[2] == 'edit') {
                    $type = $value['type'];
                }
?>
<div class="row justify-content-md-center g-0 mt-1">
    <div class="col-12 col-md-10 col-lg-8">
        <?php if(isset($value['id'])) : ?>
        <div class="alert alert-info mb-1 p-1" role="alert">
            <div class="row g-1">
                <div class="col-3 d-flex justify-content-center align-items-center">
                    <a href="/user/" class="btn btn-info btn-sm w-100 text-white">Назад</a>
                </div>
                <div class="col-9 d-flex justify-content-center align-items-center">
                    <p class="m-0 text-center">Последнее изменение: <br /><?= format_date($value['date_edit']) ?></p>
                </div>
            </div>
        </div>
        <?php endif ?>
        <?php if(isset($_SESSION['auth']['type']) && $_SESSION['auth']['type'] == 'adm') : ?>
        <div class="alert alert-danger mb-1 p-1 text-center" role="alert">
            <p class="m-0">Вы редактируете данные анкеты пользователя ID: <?= $userId ?> в режиме администратора</p>
        </div>
        <?php endif ?>
        <form method="post">
        <?php
            if($route[2] == 'edit' && $route[3] == $value['id']) {
        ?>
            <input type="hidden" name="item[<?= $route[2] ?>][id]" value="<?= $value['id'] ?>">
        <?php
            }
        ?>
            <input type="hidden" name="item[<?= $route[2] ?>][user_id]" value="<?= $userId ?>">
            <input type="hidden" name="item[<?= $route[2] ?>][type]" value="<?= $type ?>">
            <div class="card <?= (isset($errors['info']) ? 'border-danger' : '') ?> mb-1">
                <div class="card-header <?= (isset($errors['info']) ? 'text-danger' : '') ?> text-center"><b>Основная информация</b></div>
                <div class="card-body p-1">
                    <div class="row mb-1">
                        <div class="col">
                            <div class="alert alert-info mb-1 p-2 text-center" role="alert">
                                <small>Поле &laquo;Номер телефона&raquo; дожно содержать номер мобильного телефона строго 10 символов без +7 или 8. Пример: 9279174870</small>
                            </div>
                            <div class="form-floating">
                                <input required type="tel" pattern="[0-9]{10}" class="form-control <?= (isset($errors['info']['phone']) ? 'is-invalid' : '') ?>" id="phone" name="item[<?= $route[2] ?>][phone]" <?= isset($value['phone']) ? 'value="'.$value['phone'].'"' : 'placeholder="Введите значение"'?>>
                                <label for="phone">Номер телефона для анкеты <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-md-center g-1">
        <?php
            //fields info
            foreach($types[$type]['fields']['info'] as $k => $v) {
                if($v['type'] == 'text') {
        ?>
                        <div class="col-12 col-md-4">
                            <div class="form-floating">
                                <input <?= isset($v['require']) ? 'required' : '' ?> type="<?= $v['type'] ?>" class="form-control <?= (isset($errors['info'][$k]) ? 'is-invalid' : '') ?>" id="<?= $k ?>" name="item[<?= $route[2] ?>][info][<?= $k ?>]" <?= isset($value['info'][$k]) ? 'value="'.$value['info'][$k].'"' : 'placeholder="Введите значение"'?>>
                                <label for="<?= $k ?>"><?= $v['name'] ?> <?= isset($v['require']) ? '<span class="text-danger">*</span>' : '' ?></label>
                            </div>
                        </div>
        <?php
                } elseif($v['type'] == 'select') {
        ?>
                        <div class="col-12 col-md-4">
                            <div class="form-floating">
                                <select <?= isset($v['require']) ? 'required' : '' ?> class="form-select <?= (isset($errors['info'][$k]) ? 'is-invalid' : '') ?>" id="<?= $k ?>" name="item[<?= $route[2] ?>][info][<?= $k ?>]">
                                    <option value="null"<?= !isset($value['info'][$k]) || $value['info'][$k] == 'null' ? ' selected' : '' ?>>Выбрать</option>
                                    <?php
                                        foreach($v['value'] as $vk => $vv) {
                                            $selected = 0;
                                            if(isset($value['info'][$k]) && $value['info'][$k] == $vk) {
                                                $selected = 1;
                                            }
                                    ?>
                                        <option value="<?= $vk ?>"<?= isset($selected) && $selected == 1 ? ' selected' : '' ?>><?= $vv ?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                                <label for="<?= $k ?>"><?= $v['name'] ?> <?= isset($v['require']) ? '<span class="text-danger">*</span>' : '' ?></label>
                            </div>
                        </div>
        <?php
                }
            }
        ?>
                    </div>
                </div>
            </div>

            <div class="card <?= (isset($errors['service']) ? 'border-danger' : '') ?> mb-1">
                <div class="card-header <?= (isset($errors['service']) ? 'text-danger' : '') ?> text-center"><b>Список услуг</b></div>
                <div class="card-body p-1">
                    <div class="row g-1">
        <?php
            //fields services
            foreach($types[$type]['fields']['service'] as $k => $v) {
        ?>
                        <div class="col-12 col-md-4">
                            <div class="card <?= (isset($errors['service']) ? 'border-danger' : '') ?>">
                                <div class="card-body p-2">
                                    <p class="text-muted w-100 text-end text-uppercase m-0"><small><?= $v['name'] ?></small></p>
                        <?php
                            foreach($v['value'] as $vk => $vv) {
                                $checked = 0;
                                if(isset($value['service'][$k]) && in_array($vk, $value['service'][$k])) {
                                    $checked = 1;
                                }
                        ?>
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input" type="checkbox" role="switch" id="<?= $k.$vk ?>" name="item[<?= $route[2] ?>][service][<?= $k ?>][]" value="<?= $vk ?>"<?= isset($checked) && $checked == 1 ? ' checked' : ''?>>
                                        <label class="form-check-label" for="<?= $k.$vk ?>"><?= $v['name'].' '.$vv ?></label>
                                    </div>
                        <?php
                            }
                        ?>
                                </div>
                            </div>
                        </div>
        <?php
            }
        ?>
                    </div>
                </div>
            </div>

            <div class="card <?= (isset($errors['price']) ? 'border-danger' : '') ?> mb-1">
                <div class="card-header <?= (isset($errors['price']) ? 'text-danger' : '') ?> text-center"><b>Стоимость услуг</b></div>
                <div class="card-body p-1">
                    <div class="row justify-content-md-center g-1">
        <?php
            //fields price
            foreach($types[$type]['fields']['price'] as $k => $v) {
                if($v['type'] == 'text') {
        ?>
                        <div class="col-12 col-md-3">
                            <div class="form-floating">
                                <input <?= isset($v['require']) ? 'required' : '' ?> type="<?= $v['type'] ?>" class="form-control <?= (isset($errors['price'][$k]) ? 'is-invalid' : '') ?>" id="<?= $k ?>" name="item[<?= $route[2] ?>][price][<?= $k ?>]" <?= isset($value['price'][$k]) ? 'value="'.$value['price'][$k].'"' : 'placeholder="Введите значение"'?>>
                                <label for="<?= $k ?>"><?= $v['name'] ?> <?= isset($v['require']) ? '<span class="text-danger">*</span>' : '' ?></label>
                            </div>
                        </div>
        <?php
                }
            }
        ?>
                    </div>
                </div>
            </div>

            <div class="card mb-1">
                <div class="card-header text-center"><b>Место оказания услуг</b></div>
                <div class="card-body p-1">
                    <div class="row g-1">
        <?php
            //fields rao
            foreach($rao as $k => $v) {
        ?>
                        <div class="col-12 col-md-4">
                            <input type="radio" class="btn-check" id="rao<?= $k ?>" name="item[<?= $route[2] ?>][rao]" value="<?= $k ?>" autocomplete="off"<?= (isset($value['rao']) && $value['rao'] == $k) || $k == 0 ? ' checked' : '' ?>>
                            <label class="btn btn-outline-secondary w-100" for="rao<?= $k ?>"><?= $v ?></label>
                        </div>
        <?php
            }
        ?>
                    </div>
                </div>
            </div>

            <div class="card <?= (isset($errors['dopinfo']) ? 'border-danger' : '') ?> mb-1">
                <div class="card-header <?= (isset($errors['dopinfo']) ? 'text-danger' : '') ?> text-center"><b>Дополнительная информация</b></div>
                <div class="card-body p-1">
                    <div class="row g-1">
                        <div class="form-floating">
                            <textarea <?= isset($v['require']) ? 'required' : '' ?> class="form-control" id="dopinfo" name="item[<?= $route[2] ?>][dopinfo]" placeholder="Введите сюда текст о себе" style="height: 150px"><?= isset($value['dopinfo']) ? $value['dopinfo'] : ''?></textarea>
                            <label for="dopinfo">Дополнительный текст о себе</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-md-center g-0 my-2">
                <div class="col-12 col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
            }
        } elseif(isset($route[2]) && $route[2] == 'photo') {
            if(isset($route[3])) {
                if ($_SESSION['auth']['type'] == 'adm' && isset($_GET['user_id'])) {
                    $userId = $_GET['user_id'];
                } else {
                    $userId = $_SESSION['auth']['id'];
                }
                $item = item_one($route[3], $userId);
                if(!empty($item)) {
?>
<div class="row justify-content-md-center g-0">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card <?= (isset($errors['info']) ? 'border-danger' : '') ?> my-1">
            <div class="card-header text-center">
                <div class="row g-1">
                    <div class="col-3 d-flex justify-content-center align-items-center">
                        <a href="/user/" class="btn btn-info btn-sm w-100 text-white">Назад</a>
                    </div>
                    <div class="col-9 d-flex justify-content-center align-items-center">
                        <p class="m-0">
                            Редактирование фото анкеты #<?= $item['id'] ?>
                            <?= ($_SESSION['auth']['type'] == 'adm' ? '<br /><span class="text-danger">Вы редактируете фото в режиме администратора</span>' : '') ?></p>
                    </div>
                </div>
            </div>
            <div class="card-body p-1">            
<?php
        if($item['status_real'] == 1) {
?>
            <div class="alert alert-success text-center p-2 mb-1">
                <p class="mb-1">На этой анкете установлен статус <b>&laquo;Реальное фото&raquo;</b></p>
                <p class="m-0"><b>Обратите внимание:</b> при загрузке новых фотографий статус снимается и необходимо будет заново запросить подтверждение у администратора.</p>
            </div>
<?php
        }
        if($item['photo'] != '') {
?>
                <div class="row justify-content-md-center g-1">
<?php
            $photos = explode(',', $item['photo']);
            for($i = 0; $i < count($photos); ++$i) {
                if(file_exists($site['path'].'/media/photo/'.$item['id'].'/'.$photos[$i].'.jpg')) {
?>
                    <div class="col-6 col-md-3 col-lg-2">
                        <div class="card mx-auto" style="max-width:250px">
                            <img src="<?= thumb($photos[$i], $item['id'], ['width' => 250, 'height' => 250]) ?>" class="card-img-top">
                            <div class="card-body p-1">
                                <button class="btn btn-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#photo<?= $photos[$i] ?>">Удалить</button>
                            </div>
                        </div>
                        <!-- modal<? $photos[$i] ?> -->
                        <div class="modal fade" id="photo<?= $photos[$i] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form class="m-0" method="post">
                                    <input type="hidden" name="item[photo][del][id]" value="<?= $item['id'] ?>">
                                        <input type="hidden" name="item[photo][del][file]" value="<?= $photos[$i] ?>">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Удаление фото: <?= $photos[$i] ?>.jpg</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-2">
                                            <img src="<?= $site['url'].'/media/photo/'.$item['id'].'/'.$photos[$i].'.jpg' ?>" class="img-fluid img-thumbnail">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                            <button type="submit" class="btn btn-danger">Да, удалить</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- !modal<? $photos[$i] ?> -->
                    </div>
<?php
                }
            }
?>
                </div>
<?php
        } else {
    ?>
                <div class="alert alert-danger mb-0" role="alert">
                    <p class="m-0 text-center"><strong>Нет загруженных фотографий.</strong><br /> Добавьте хотя бы одну так как активация анкеты без фотографий невозможна.</p>
                </div>

    <?php
        }
    ?>
            </div>
            <div class="card-footer">
                <form enctype="multipart/form-data" accept="image/*" method="post" class="m-0">
                    <input type="hidden" name="item[photo][add][id]" value="<?= $item['id'] ?>">
                    <div class="row g-1">
                        <div class="col-12 col-md-8">
                            <input class="form-control" type="file" name="upload[]" multiple>
                        </div>
                        <div class="col-12 col-md-4">
                            <button type="submit" class="btn btn-primary w-100">Загрузить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
                } else {
                    echo redirect($site['url'].'/user/');
                }
            }
        }
    } else {
        echo redirect($site['url'].'/');
    }
?>