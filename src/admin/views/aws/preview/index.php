<?php

/**
 * NewsPreviewActiveWindow Index View.
 *
 * @var \luya\admin\ngrest\base\ActiveWindowView $this
 * @var \luya\admin\ngrest\base\NgRestModel $model
 */
?>
<script>
zaa.bootstrap.register('InlineController', ['$scope', function($scope) {
    $scope.reload = function() {
        $scope.$parent.reloadActiveWindow()
        $scope.$parent.toast.success('Preview reloaded')
    }
}]);
</script>
<div ng-controller="InlineController" class="pb-3">
    Online: <?= Yii::$app->formatter->asBoolean($model->is_online); ?>
    <button type="button" ng-click="reload()" class="float-right btn btn-primary"><i class="material-icons">refresh</i></button>
</div>

<iframe
    frameborder="0"
    width="100%"
    height="900"
    src="<?= $url; ?>"
    style="width:100%;border:none;overflow:hidden;"
></iframe>
