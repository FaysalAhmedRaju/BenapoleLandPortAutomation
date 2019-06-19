

<div class="modal-body">
    Please Input Weight for @{{ row }}@{{ column }}
    <input type="text" class="form-control"
           ng-model="weight" />
    <button class="btn btn-primary"
            ng-click="save()">@{{ button_text }}</button>
    <br>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" ng-click="close()">Close</button>
</div>