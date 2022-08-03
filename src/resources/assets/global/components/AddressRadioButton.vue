<template>
<div class="col-md-12">
    <div class="input-group" v-for="(radio, index) in radio_array" :key="index">
        <div class="skin skin-square"  style="margin-right:5px;">
            <div class="form-group" style="margin-bottom:10px;">
                <input :id="type+'-'+radio.id" type="radio" :class="'radio-'+id" :name="type" :value="radio.id" :checked="(value == radio.id)">
                <label>{{radio.address1}} {{radio.address2}} {{radio.city}} {{radio.zip}} {{radio.state}} {{radio.country}}</label>
            </div>
        </div>
    </div>
    <div class="input-group">
        <div class="skin skin-square"  style="margin-right:5px;">
            <div class="form-group" style="margin-bottom:10px;">
                <input :id="type+'-new'" type="radio" :class="'radio-'+id" :name="type" value="0" :checked="(value == 0)">
                <label>New address</label>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        props:['type','parent', 'radio_array', 'value'],
        data(){
            return {
                id:Helpers.guidGenerator()
            }  
        },
        // watch:{
        //     value(val){
        //         $('#'+this.type+'-'+val).iCheck('check');
        //         // $(".radio-"+this.id).iCheck('check');
        //     }
        // },
        mounted() {
            var self = this;
            this.$nextTick(function(){
                $(".radio-"+self.id).iCheck({
                    checkboxClass: 'icheckbox_square-red',
                    radioClass: 'iradio_square-red',
                });
                $(".radio-"+self.id).on('ifChecked', function(val){
                    self.$emit('radioHandle', {
                        type: self.type,
                        parent: self.parent,
                        val: val.currentTarget.value});
                });
                $(".radio-"+self.id).on('ifUnchecked', function(val){
                    self.$emit('radioHandle', {
                        type: self.type,
                        parent: self.parent,
                        val: val.currentTarget.value});
                });
            })
        }
    }
</script>