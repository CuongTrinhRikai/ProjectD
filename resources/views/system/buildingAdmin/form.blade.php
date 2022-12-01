@extends('system.layouts.form')
@section('inputs')
    @if(isset($item))
        {{ Form::hidden('id',$item->id,['class'=>'form-control']) }}
    @endif
    <x-system.form.form-group-space :input="['name' => 'username', 'label' => 'Building Admin ID','default'=> old('username') ?? $item->username ?? old('username'), 'error' => $errors->first('username'),'label-required'=>'true']" />
    <x-system.form.form-group-space :input="['name' => 'name', 'label' => 'Building Admin Name','default'=>  old('name') ?? $item->name ?? old('name'), 'error' => $errors->first('name'),'label-required'=>'true']" />
    <x-system.form.form-group-space :input="[ 'name' => 'contractor_id', 'label'=> 'Contractor','label-required'=>'true']">
        <x-slot name="inputs">
            <x-system.form.input-select :input="[ 'name' => 'contractor_id', 'label'=> 'Contractor','default' => old('contractor_id') ?? $item->contractor_id ?? old('contractor_id') , 'options' => $contractors, 'placeholder' => 'Select a Contractor', 'error' => $errors->first('contractor_id')]" />
        </x-slot>
    </x-system.form.form-group-space>
    <div class="form-group row" id="selected_mansion">
        <label for="mansion_id" class="col-sm-3 col-form-label ">
           {{translate('Mansion')}} <span style="color: red">*</span>
        </label>
        @if(!old('mansion_id') && !isset($selectedmansion))
            <input type="hidden" name="list_mansion" value="{{ json_encode($contractors) }}">
        @endif
        @if(old('mansion_id'))
            <input type="hidden" name="old_mansion_selected" value="{{ json_encode(old('mansion_id')) }}">
        @endif
        <div class="col-sm-6">
            <div @if($errors->has('mansion_id')) class="error-msg" @endif>
                @if($errors->has('mansion_id'))
                    <img src="{{asset('images/image.svg')}}">
                @endif
                {{ Form::select('mansion_id[]',$mansions, null,['class'=>'form-control multiple','multiple' => 'multiple']) }}
                @error('mansion_id')
                <p class="invalid-text text-danger" style="text-align: left;font-size: 80%">{{translate($message)}}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-group row" id="">
        <label for="business_category" class="col-sm-3 col-form-label ">
            {{translate('Business Category')}} <span style="color: red">*</span>
        </label>
        <div class="col-sm-6">
            <div @if($errors->has('business_category')) class="error-msg" @endif>
                @if($errors->has('business_category'))
                    <img src="{{asset('images/image.svg')}}">
                @endif
                {{ Form::select('business_category[]',$business,isset($selectedbusiness)? $selectedbusiness : null,['class'=>'form-control multiple-business', 'multiple' => 'multiple']) }}
                @error('business_category')
                <p class="invalid-text text-danger" style="text-align: left;font-size: 80%">{{translate($message)}}</p>
                @enderror
            </div>
        </div>
    </div>

    @if(!isset($item))
    <x-system.form.form-group-space :input="[ 'name' => 'password', 'label'=> 'Password','label-required'=>true, 'type' => 'password', 'default' => old('password'), 'error' => $errors->first('password')]" />
    <x-system.form.form-group-space :input="[ 'name' => 'password_confirmation', 'label'=> 'Confirm Password','label-required'=>true, 'type' => 'password', 'default' => old('password_confirmation'), 'error' => $errors->first('password_confirmation')]" />
    @endif
    @section('actionButtons')
        <div class="offset-sm-2 col-sm-10 guide" style="margin-left: 25%">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-plus-circle" aria-hidden="true"></i> {{ !isset($item) ? translate('Create') : translate('Update')}}
            </button>
            <a href="{{url($indexUrl)}}" class="btn btn-secondary">
                <em class="far fa-window-close"></em> {{ translate('Cancel') }}
            </a>
        </div>
    @stop
@endsection

@section('scripts')
    <script>

        $(document).ready(function () {
            $('.multiple').select2({placeholder: `{{translate('select mansion')}}`});
            $('.multiple-business').select2({placeholder: `{{translate('select business category')}}`});
        });

        $('select[name="contractor_id"]').change(function () {
           if ($('select[name="contractor_id"]').val() >= 1){
               $('#selected_mansion').show();
               opt = {
                   url: '{{ route('get-mansion', '') }}' + '/' + $('select[name="contractor_id"]').val(),
                   method: "GET"
               };
               axios(opt)
                   .then(function (res) {
                       return res.data
                   }).then(function (data) {
                   let defaultListMansion = data;
                   let selectArr = $('select[name="mansion_id[]"]')
                   selectArr.empty();
                   defaultListMansion.forEach(function (value) {
                       addOption(value, selectArr);

                   })
               })
           } else {
               $('#selected_mansion').hide();
           }
        })

        $('select[name="contractor_id"]').ready(function () {
            if ($('input[name="list_mansion"]').val() != null) {
                //default creation screen
                @if(!$errors->has('mansion_id'))
                    $('#selected_mansion').hide();
                @endif
            }
            if ($('input[name="old_mansion_selected"]').val() != null) {
                $('#selected_mansion').show();
                dataMansionOld();
            } else {
                dataMansionEdit();
            }
        })

        function dataMansionOld() {
            let defaultListMansion = JSON.parse($('input[name="old_mansion_selected"]').val());
            let selectArr = $('select[name="mansion_id[]"]')
            selectArr.empty();
            defaultListMansion.forEach(function (value) {
                dataMansionSelected(value, selectArr);
            })
        }

        function dataMansionEdit() {
            optEdit = {
                url: '{{ route('get-mansion-edit', '') }}' + '/' + $('input[name="id"]').val(),
                method: "GET"
            };
            axios(optEdit)
                .then(function (res) {
                    return res.data
                }).then(function (data) {
                let defaultListMansion = data;
                let selectArr = $('select[name="mansion_id[]"]')
                selectArr.empty();
                defaultListMansion.forEach(function (value) {
                    dataMansionSelected(value, selectArr, 'mansion_id');

                })
            })
        }

        function dataMansionSelected(value, selectArr, id = null) {
            optContractor = {
                url: '{{ route('get-mansion', '') }}' + '/' + $('select[name="contractor_id"]').val(),
                method: "GET"
            };

            axios(optContractor)
                .then(function (res) {
                    return res.data
                }).then(function (data) {
                let defaultListMansion = data;
                defaultListMansion.forEach(function (item) {
                    if (Number(item['id']) === Number(id ? value[id] : value)) {
                        let $option = $("<option selected></option>")
                            .attr("value", item['id'])
                            .text(item['mansion_name']);
                        selectArr.append($option);
                    } else {
                        addOption(item, selectArr);
                    }
                })
            })
        }

        function addOption(item, selectArr) {
            let $option = $("<option></option>")
                .attr("value", item['id'])
                .text(item['mansion_name']);
            selectArr.append($option);
        }

        window.onload = () => {
            let old_contractor = '{{old('contractor_id')}}'
            let old_mansion = '{{json_encode(old('mansion_id'))}}'
            if(old_contractor !== '' && old_mansion === 'null'){
                console.log(12)
                $('select[name="contractor_id"]').trigger('change')
            }
        }
    </script>
@endsection

