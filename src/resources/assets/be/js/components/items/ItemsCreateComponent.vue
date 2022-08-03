<template>

    <div>
        <div id="form-action-layouts" class="col-md-12">
            <div class="row match-height">
                <div class="col-md-12">
                    <div class="card" style="height: 100% !important;">
                        <div class="card-header">
                            <a class="heading-elements-toggle">
                                <i class="fa fa-ellipsis-v font-medium-3"></i>
                            </a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li>
                                        <a data-action="collapse">
                                            <i class="ft-minus"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-action="reload">
                                            <i class="ft-rotate-cw"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-action="expand">
                                            <i class="ft-maximize"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-action="close">
                                            <i class="ft-x"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collpase show">
                            <div class="card-body">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <h4 class="form-section">Add product</h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8 mb-2">
                                            <div class="form-group">
                                                <label for="userinput1">Name</label>
                                                <input type="text" v-model="item.name" name="name" id="name" class="form-control" v-bind:class="{'input-error-select' : error.name}"
                                                    placeholder="Type product name">
                                                <span v-if="error.name" class="message-error">{{error_message.name}}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="category">Category</label>
                                            <div class="input-group">
                                                <select id="select2-category" v-model="item.category_id" class="select2 form-control" style="width: 100%" data-placeholder="Select product category...">
                                                    <option v-for="(category, index) in current_categories" :key="index" :value="category.id">{{category.name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8 mb-2">
                                            <div class="form-group">
                                                <label for="userinput2">Description</label>
                                                <textarea name="description" v-model="item.description" id="description" rows="5" class="form-control" v-bind:class="{'input-error-select' : error.description}"
                                                    placeholder="Type item description"></textarea>
                                                <span v-if="error.description" class="message-error">{{error_message.description}}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <div class="form-group">
                                                <label>Publish Date</label>
                                                <input type='text' v-model="item.publish" id="publish" class="form-control" placeholder="Select publish date" />
                                            </div>
                                            <div class="row" style="margin-top:40px;">
                                                <div class="col-6 col-md-6">
                                                    <fieldset>
                                                        <div class="input-group">
                                                            <div class="skin skin-square">
                                                                <div class="form-group text-right">
                                                                    <input type="checkbox" id="active">
                                                                    <label for="active">Active</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-6 col-md-6">
                                                    <fieldset>
                                                        <div class="input-group">
                                                            <div class="skin skin-square">
                                                                <div class="row" style="margin-left: 0px">
                                                                    <div class="col-xs-4">
                                                                        <input type="checkbox" id="taxable">
                                                                        <label>Is taxable</label>
                                                                    </div>
                                                                    <!-- <div class="col-xs-8">
                                                                        <div v-if="item.taxable">
                                                                            <input v-model="item.tax_percent" type="text" class="form-control" name="tax_percent" id="tax_percent" style="width: 100%"
                                                                                placeholder="Type tax percent">
                                                                        </div>
                                                                    </div> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6 col-lg-3 mb-2">
                                                            <div class="form-group">
                                                                <label>Product type</label>
                                                                <select id="select2-type" v-model="item.type" class="select2 form-control" style="width: 100%" data-placeholder="Select product type...">
                                                                    <option v-for="(type, index) in item_types" :key="index" :value="type.name">{{type.label}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-lg-3 mb-2">
                                                            <label>SKU</label>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input type="text" v-model="item.sku" class="form-control" v-bind:class="{'input-error-select' : error.sku}" name="sku" id="sku"
                                                                        placeholder="Type sku" aria-describedby="basic-addon4">
                                                                </div>
                                                            </fieldset>
                                                            <span v-if="error.sku" class="message-error">{{error_message.sku}}</span>
                                                        </div>
                                                        <div class="col-md-6 col-lg-3 mb-2">
                                                            <label>UPC</label>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input type="text" v-model="item.upc" class="form-control" v-bind:class="{'input-error-select' : error.upc}" name="upc" id="upc"
                                                                        placeholder="Type upc" aria-describedby="basic-addon4">
                                                                </div>
                                                            </fieldset>
                                                            <span v-if="error.upc" class="message-error">{{error_message.upc}}</span>
                                                        </div>
                                                        <div v-if="item.type=='physical'" class="col-md-6 col-lg-3 mb-2">
                                                            <label>Quantity on hand</label>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input type="text" v-model="item.total_qty" class="form-control" v-bind:class="{'input-error-select' : error.total_qty}"
                                                                        name="total_qty" id="total_qty" placeholder="Type total quantity" aria-describedby="basic-addon4">
                                                                </div>
                                                            </fieldset>
                                                            <span v-if="error.total_qty" class="message-error">{{error_message.total_qty}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6 col-lg-3 mb-2">
                                                            <label>MSRP</label>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input type="text" v-model="item.msrp" v-bind:class="{'input-error-select' : error.msrp}" class="form-control" name="price" id="msrp" placeholder="Type msrp" aria-describedby="basic-addon4">
                                                                </div>
                                                            </fieldset>
                                                            <span v-if="error.msrp" class="message-error">{{error_message.msrp}}</span>
                                                        </div>
                                                        <div class="col-md-6 col-lg-3 mb-2">
                                                            <label>Regular price</label>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input type="text" v-model="item.price" class="form-control" v-bind:class="{'input-error-select' : error.price}" name="price"
                                                                        id="price" placeholder="Type price" aria-describedby="basic-addon4">
                                                                </div>
                                                            </fieldset>
                                                            <span v-if="error.price" class="message-error">{{error_message.price}}</span>
                                                        </div>
                                                        <div class="col-md-6 col-lg-3 mb-2">
                                                            <label>Discount Percent</label>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input type="text" v-model="item.discount_percent" v-bind:class="{'input-error-select' : error.discount_percent}" class="form-control square" name="discount_percent" id="discount_percent"
                                                                        placeholder="Type discount percent">
                                                                </div>
                                                            </fieldset>
                                                            <span v-if="error.discount_percent" class="message-error">{{error_message.discount_percent}}</span>
                                                        </div>
                                                        <div v-if="item.type=='physical'" class="col-md-6 col-lg-3 mb-2">
                                                            <label>Products cost</label>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input v-model="item.cost" type="text" v-bind:class="{'input-error-select' : error.cost}" class="form-control" name="cost" id="cost" placeholder="Type product cost">
                                                                </div>
                                                            </fieldset>
                                                            <span v-if="error.tax_percent" class="message-error">{{error_message.tax_percent}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div v-if="item.type=='physical'" class="col-md-6 col-lg-3 mb-2">
                                                            <label>Weight</label>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input v-model="item.weight" type="text" class="form-control" name="weight" id="weight" v-bind:class="{'input-error-select' : error.weight}"
                                                                        placeholder="Type product weight">
                                                                    <div class="input-group-btn">
                                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            {{item.weight_unit}}
                                                                        </button>
                                                                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                            <a v-for="(w_u,index) in weight_units" :key="index" class="dropdown-item" @click="setWeightUnit(w_u)">{{w_u}}</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                            <span v-if="error.weight" class="message-error">{{error_message.weight}}</span>
                                                        </div>
                                                        <div v-if="item.type=='physical'" class="col-md-6 col-lg-3 mb-2">
                                                            <label>Length</label>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input type="text" v-model="item.length" class="form-control square" name="length" id="length" v-bind:class="{'input-error-select' : error.length}"
                                                                        placeholder="Type length">
                                                                    <div class="input-group-btn">
                                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            {{item.length_unit}}
                                                                        </button>
                                                                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                            <a v-for="(l_g, index) in length_units" :key="index" class="dropdown-item" @click="setLengthUnit(l_g)">{{l_g}}</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                            <span v-if="error.length" class="message-error">{{error_message.length}}</span>
                                                        </div>
                                                        <div v-if="item.type=='physical'" class="col-md-6 col-lg-3 mb-2">
                                                            <label>Height</label>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input type="text" v-model="item.height" class="form-control square" name="height" id="height" v-bind:class="{'input-error-select' : error.height}"
                                                                        placeholder="Type height">
                                                                    <div class="input-group-btn">
                                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            {{item.height_unit}}
                                                                        </button>
                                                                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                            <a v-for="(h_u, index) in length_units" :key="index" class="dropdown-item" @click="setHeightUnit(h_u)">{{h_u}}</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                            <span v-if="error.height" class="message-error">{{error_message.height}}</span>
                                                        </div>
                                                        <div v-if="item.type=='physical'" class="col-md-6 col-lg-3 mb-2">
                                                            <label>Width</label>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input type="text" v-model="item.width" class="form-control square" name="width" id="width" v-bind:class="{'input-error-select' : error.width}"
                                                                        placeholder="Type width">
                                                                    <div class="input-group-btn">
                                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            {{item.width_unit}}
                                                                        </button>
                                                                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                            <a v-for="(w_u, index) in length_units" :key="index" class="dropdown-item" @click="setWidthUnit(w_u)" >{{w_u}}</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                            <span v-if="error.width" class="message-error">{{error_message.width}}</span>
                                                        </div>
                                                        <!-- <div v-if="item.type=='physical'" class="col-lg-4 col-xl-2 mb-2">
                                                            <div class="form-group">
                                                                <label>Units in box</label>
                                                                <div class="input-group">
                                                                    <input type="text" v-model="item.units_in_box" class="form-control square" name="box_unit" id="box_unit" placeholder="Type units in box">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div v-if="item.type=='physical'" class="col-lg-4 col-xl-2 mb-2">
                                                            <div class="form-group">
                                                                <label>Unit of measurement</label>
                                                                <div class="input-group">
                                                                    <input type="text" v-model="item.um" class="form-control square" name="um" id="um" placeholder="Type unit of measurment">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div v-if="item.type=='physical'" class="col-md-2 mb-2">
                                                            <label>Package type</label>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input type="text" v-model="item.package_type" class="form-control" name="package_type" id="package_type" placeholder="Type package type"
                                                                        aria-describedby="basic-addon4">
                                                                </div>
                                                            </fieldset>
                                                        </div> -->
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div v-if="item.marketplaceItems.length > 0" class="col-md-6 mb-2">
                                                            <div>
                                                                This Item is synced in the following marketplaces:
                                                                <br>
                                                                <ul style="list-style: none; padding: 10px">
                                                                    <li v-for="(market, index) in item.marketplaceItems" :key="index">
                                                                        <img :src="'/images/marketplace/' + market.name + '.png'" style="width: 80px; height: auto;">
                                                                        <button @click="removeSync(market.id, market.name)" type="button" class="btn mr-1 mb-1 btn-danger btn-sm" style="margin-top: 5px">
                                                                            <i class="fa fa-trash"></i>
                                                                            <span>Remove sync from {{market.name}}</span>
                                                                        </button>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div v-else class="col-lg-4 col-xl-2 mb-2">
                                                            <div v-if="active_marketplaces.length > 0">
                                                                <fieldset>
                                                                    <div class="form-group">
                                                                        <div class="btn-group mr-1 mb-1">
                                                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="ft-link-2"></i>
                                                                                <span>Marketplace link</span>
                                                                            </button>
                                                                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 40px, 0px); top: 0px; left: 0px; will-change: transform; width: 100%">
                                                                                <a v-for="(marketplace, index) in active_marketplaces" class="dropdown-item" data-toggle="modal" :data-target="'#link' + marketplace"
                                                                                    href="#" :key="index">
                                                                                    <span style="padding-right: 10px">
                                                                                        <b>{{marketplace}}</b>
                                                                                    </span>
                                                                                    <img :src="'/images/marketplace/' + marketplace + '.png'" style="width: 50px; height: auto;">
                                                                                    <div class="dropdown-divider" style="border-top: 1px solid rgb(248, 242, 242)"></div>
                                                                                </a>

                                                                                <!--  <a class="dropdown-item" href="#">Another action</a>
                                                                                        <a class="dropdown-item" href="#">Something else here</a>
                                                                                        <div class="dropdown-divider"></div>
                                                                                        <a class="dropdown-item" href="#">Separated link</a> -->
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12" style="margin-bottom:20px;">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div>
                                                                <label>Upload product pictures</label>
                                                                <form action="#" class="dropzone dropzone-area dz-clickable" id="dpz-multiple-files" v-bind:class="{'error-dropzone' : error.picture}">
                                                                    <div class="dz-message">Drop Files Here To Upload</div>
                                                                </form>
                                                            </div>
                                                            <span v-if="error.picture" class="message-error">{{error_message.picture}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-12 mb-2">
                                                    <strong>Package Types</strong>
                                                    <a @click="openPackageModal()" style="float:right;">Add package</a>
                                                </div>   
                                            </div>
                                            <div v-if="item.package_type.length > 0" class="col-md-12" style="margin-bottom:25px">  
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0" >
                                                        <thead>
                                                            <tr>
                                                                <th style="border-color:#fff;padding:5px;">Name</th>
                                                                <th style="border-color:#fff;padding:5px;">Units</th>
                                                                <th style="border-color:#fff;padding:5px;"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(pack, index) in item.package_type" :key="index">
                                                                <td class="text-truncate" style="border-color:#fff;padding:5px;">{{pack.name}}</td>
                                                                <td class="text-truncate" style="border-color:#fff;padding:5px;">{{pack.units}}</td>
                                                                <td class="text-truncate" style="text-align: right;border-color:#fff;padding:5px;">
                                                                    <a @click="removePackage(pack.id)" style="color: red;margin-top:2px; margin-bottom:2px;" title="Remove">
                                                                        <i class="fa fa-trash-o"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div v-else style="margin-bottom:45px;text-align:center;">
                                                <strong>No packages</strong>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12 mb-2">
                                                    <strong>Variants</strong>
                                                    <a @click="openOptionModal()" style="float:right;">Edit options</a><br>
                                                    <a v-if="item.options.length > 0" @click="openVariantModal()" style="float:right;">Add variant</a>
                                                </div>  
                                                <div v-if="item.variants.length > 0" class="col-md-12" style="margin-bottom:25px">  
                                                    <div class="table-responsive">
                                                        <table class="table table-hover mb-0" >
                                                            <thead>
                                                                <tr>
                                                                    <th v-for="(opt,index) in item.options" style="border-color:#fff;padding:5px;text-transform:capitalize" :key="index">{{opt.label}}</th>
                                                                    <th style="border-color:#fff;padding:5px;"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="(variant, index) in item.variants" :key="index">
                                                                    <td v-for="(opt,index) in item.options" :key="index"  class="text-truncate" style="border-color:#fff;padding:5px;">{{variant[opt.label]}}</td>
                                                                    <td class="text-truncate" style="text-align: right;border-color:#fff;padding:5px;">
                                                                        <a @click="removeVariant(index)" style="color: red;margin-top:2px; margin-bottom:2px;" title="Remove">
                                                                            <i class="fa fa-trash-o"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div> 
                                                <div class="col-md-12" v-else style="margin-bottom:45px;text-align:center;">
                                                    <strong>No variants</strong>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row" v-if="collections && collections.length > 0">
                                                <div class="col-md-12 mb-2">
                                                    <strong>Collections</strong>
                                                    <a @click="openCollectionModal()" style="float:right;">Add collection</a><br>
                                                </div>  
                                                <div v-if="item.collections.length > 0" class="col-md-12" style="margin-bottom:25px">  
                                                    <div class="table-responsive">
                                                        <table class="table table-hover mb-0" >
                                                            <tbody>
                                                                <tr v-for="(collect, index) in item.collections" :key="index">
                                                                    <td style="border-color:#fff;padding:5px;">{{collect.name}}</td>
                                                                    <td class="text-truncate" style="text-align: right;border-color:#fff;padding:5px;">
                                                                        <a @click="removeCollection(collect.id)" style="color: red;margin-top:2px; margin-bottom:2px;" title="Remove">
                                                                            <i class="fa fa-trash-o"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div> 
                                                <div class="col-md-12" v-else style="margin-bottom:45px;text-align:center;">
                                                    <strong>Product doesn't belong to any collection</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-right" style="margin-top: 15px">
                                    <div class="col-12">
                                        <a href="/admin/store/items" class="btn btn-warning mr-1">
                                            <i class="ft-x"></i> Cancel
                                        </a>
                                        <template v-if="item.id > 0">
                                            <button @click="updateItem(item.id)" type="button" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i>
                                                <span>Update</span>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button @click="addItem()" type="button" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i>
                                                <span>Save</span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade text-left show" id="linkamazon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel1">Sync this item in Amazon</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-3 label-control text-center" style="margin-top: 10px">
                                <i class="ft-help-circle" data-toggle="tooltip" data-placement="right" title="Amazon Standard Identification Number" style="font-size: 16px"></i> ASIN :</label>
                            <div class="col-md-9">
                                <input type="text" v-model="amazon.asin" v-bind:class="{'input-error-select' : error.asin}" class="form-control" id="asin">
                                <span v-if="error.asin" class="message-error">{{error_message.asin}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button @click="syncWithAmazon()" type="button" class="btn btn-outline-primary" id="onhidebtn">Sync on</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade text-left show" id="units-package" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
            <div class="modal-dialog modal-xs" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel1">Add package</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Package Types</label>
                                    <div class="input-group">
                                        <select id="select2-packages" class="select2-placeholder form-control" style="width: 100%">
                                        </select>
                                    </div>
                                    <!-- <span v-if="error.apply_to" class="message-error">{{error_message.apply_to}}</span> -->
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input type="text" placeholder="Type how many unit by package selected" v-model="package_units" v-bind:class="{'input-error-select' : error.package_units}" class="form-control" id="package_units">
                                <span v-if="error.package_units" class="message-error">{{error_message.package_units}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button @click="addPackage()" type="button" class="btn btn-outline-primary" id="onhidebtn">Add</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade text-left show" id="options-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
            <div class="modal-dialog modal-xs" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel1">Options</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-12">  
                                <div class="form-group">
                                    <label>Options</label>
                                    <div class="input-group">
                                        <select id="select2-options" class="select2-placeholder form-control" style="width: 100%">
                                        </select>
                                        <span class="input-group-btn">
                                            <button @click="addOption()" class="btn btn-primary" type="button">
                                                <i class="ft-plus"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <!-- <span v-if="error.apply_to" class="message-error">{{error_message.apply_to}}</span> -->
                                </div>
                            </div>
                            <div v-if="item.options.length > 0" class="col-md-12">  
                                <label>Current options:</label> 
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0" >
                                        <thead>
                                            <tr>
                                                <th style="border-color:#fff;padding:5px;">Name</th>
                                                <th style="border-color:#fff;padding:5px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(opt, index) in item.options" :key="index">
                                                <td class="text-truncate" style="border-color:#fff;padding:5px;">{{opt.label}}</td>
                                                <td class="text-truncate" style="text-align: right;border-color:#fff;padding:5px;">
                                                    <a @click="removeOption(opt.id)" style="color: red;margin-top:2px; margin-bottom:2px;" title="Remove">
                                                        <i class="fa fa-trash-o"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade text-left show" id="variants-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
            <div class="modal-dialog modal-xs" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel1">Variants</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row" id="variants-form">
                            <div class="col-md-12" v-for="(opt, index) in item.options" :key="index">
                                <div class="form-group">
                                    <label style="text-transform:capitalize;">{{opt.label}}</label>
                                    <div class="input-group">
                                        <input type="text" :placeholder="'Type '+opt.label" class="form-control" :data-id="opt.label">
                                    </div>
                                    <!-- <span v-if="error.apply_to" class="message-error">{{error_message.apply_to}}</span> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                         <button @click="addVariants()" type="button" class="btn btn-outline-primary" id="onhidebtn">Add</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade text-left show" id="collections-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
            <div class="modal-dialog modal-xs" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel1">Collections</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-12">  
                                <div class="form-group">
                                    <label>Collections</label>
                                    <div class="input-group">
                                        <select id="select2-collections" class="select2-placeholder form-control" style="width: 100%">
                                        </select>
                                    </div>
                                    <!-- <span v-if="error.apply_to" class="message-error">{{error_message.apply_to}}</span> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
                         <button @click="addCollection()" type="button" class="btn btn-outline-primary" id="onhidebtn">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Helpers from '../../../../../../misc/helpers.js';
    export default {
        props: ['categories', 'editable_item', 'active_marketplaces','collections'],
        mixins: [Helpers],
        data() {
            return {
                current_categories: this.categories,
                item: {
                    id: '',
                    name: '',
                    description: '',
                    publish: '',
                    price: '',
                    msrp: '',
                    total_qty: '',
                    discount_percent: '',
                    um: '',
                    category_id: '',
                    sku: '',
                    upc: '',
                    height: '',
                    height_unit: 'in',
                    weight: '',
                    weight_unit: 'lb',
                    width: '',
                    width_unit: 'in',
                    length: '',
                    length_unit: 'in',
                    cost: '',
                    marketplaceItems: [],
                    /* barcode: '', */
                    package_type: [],
                    // units_in_box: '',
                    // box_unit: '',
                    taxable: false,
                    tax_percent: '',
                    active: true,
                    type: 'physical',
                    options:[],
                    variants:[],
                    collections:[]
                },
                item_types: [
                    {
                        label: 'Physical product',
                        name: 'physical'
                    },
                    {
                        label: 'Digital product',
                        name: 'digital'
                    }
                ],
                previos_qty: '',

                amazon: {
                    asin: ''
                },

                weight_units: ['lb', 'oz', 'g', 'kg'],
                length_units: ['mm', 'cm', 'in'],

                dropzone: {},
                publish_datepicker: {},
                error: {
                    upc: false,
                    name: false,
                    description: false,
                    total_qty: false,
                    price: false,
                    asin: false,
                    picture: false,
                    width: false,
                    height: false,
                    weight: false,
                    length: false,
                    msrp:false,
                    tax_percent: false,
                    cost: false,
                    discount_percent: false,
                    sku:false,
                    package_units:false
                },
                error_message: {
                    upc: '',
                    name: '',
                    description: '',
                    total_qty: '',
                    price: '',
                    asin: '',
                    picture: '',
                    width: '',
                    height: '',
                    weight: '',
                    length: '',
                    msrp:'',
                    tax_percent: '',
                    cost: '',
                    discount_percent: '',
                    sku:'',
                    package_units:'',
                },
                package_id:0,
                package_name:'',
                package_units:'',
                option: {
                    id:0,
                    label:''
                },
                collection: {
                    id:0,
                    name:''
                }
            }
        },
        methods: {
            openPackageModal(){
                $("#units-package").modal('show');
            },
            openOptionModal(){
                $("#options-modal").modal('show');
            },
            openCollectionModal(){
                $("#collections-modal").modal('show');
            },
            openVariantModal(){
                $("#variants-modal").modal('show');
            },
            addVariants(){
                var _this = this;
                var params ={};
                $("#variants-form").find('input').each(function(){
                    // for(var i in _this.item.variants){
                    //     if(_this.item.variants[i][$(this).data("id")] == ){
                    //         _this.item.variants.splice(i, 1);
                    //     }
                    // }
                    var data_id = $(this).data("id");
                    
                    params[data_id] = $(this).val();
                });
                this.item.variants.push(params);
            },
            addCollection(){
                if(this.collection.id > 0){
                    var exist = false;
                    for(var i in this.item.collections){
                        if(this.item.collections[i].id == this.collection.id){
                            exist = true;
                        }
                    }
                    if(!exist){
                        this.item.collections.push(this.collection);
                    }
                }
            },
            addPackage(){
                if(this.package_units != '' && this.package_id > 0){
                    var exist = false;
                    for(var i in this.item.package_type){
                        if(this.item.package_type[i].id == this.package_id){
                            exist = true;
                        }
                    }
                    if(!exist){
                        this.item.package_type.push({
                            id: this.package_id,
                            name: this.package_name,
                            units: this.package_units,
                        });
                    }
                    $('#select2-packages').val(null).trigger('change');
                    $("#units-package").modal('hide');
                }else{
                    this.error.package_units = true;
                    this.error_message.package_units = 'Units in package is required';
                }
            },
            removeOption(id){
                for(var i in this.item.options){
                    if(this.item.options[i].id == id){
                        this.item.options.splice(i,1);
                    }
                }
            },
            removeCollection(id){
                console.log(id);
                for(var i in this.item.collections){
                    if(this.item.collections[i].id == id){
                        this.item.collections.splice(i,1);
                    }
                }
            },
            removeVariant(index){
                this.item.variants.splice(index,1);
            },
            removePackage(id){
                for(var i in this.item.package_type){
                    if(this.item.package_type[i].id == id){
                        this.item.package_type.splice(i,1);
                    }
                }
            },
            addItem() {
                if (this.item.type == 'physical') {
                    if (this.item.name != "" && this.item.description != "" && this.item.total_qty != "" && this.item.price != ""
                        && this.item.height != "" && this.item.weight != "" && this.item.length != "" && this.item.width != "") {
                        this.submitItem();
                    } else {
                        if (this.item.name == "") {
                            this.error.name = true;
                            this.error_message.name = 'Product title is required';
                        }
                        if (this.item.description == "") {
                            this.error.description = true;
                            this.error_message.description = 'Product description is required';
                        }
                        /*      if (this.item.upc == "") {
                                 this.error.upc = true;
                                 this.error_message.upc = 'Universal product code is required';
                             } */
                        if (this.item.price == "") {
                            this.error.price = true;
                            this.error_message.price = 'Product price is required';
                        }
                        if (this.item.total_qty == "") {
                            this.error.total_qty = true;
                            this.error_message.total_qty = 'Total quantity is required';
                        }
                        if (this.item.height == "") {
                            this.error.height = true;
                            this.error_message.height = 'Product height  is required';
                        }
                        if (this.item.weight == "") {
                            this.error.weight = true;
                            this.error_message.weight = 'Product weight  is required';
                        }
                        if (this.item.length == "") {
                            this.error.length = true;
                            this.error_message.length = 'Product length  is required';
                        }
                        if (this.item.width == "") {
                            this.error.width = true;
                            this.error_message.width = 'Product width  is required';
                        }
                        /*  if (this.dropzone.files.length == 0) {
                             this.error.picture = true;
                             this.error_message.picture = 'At least one picture about this product is required';
                         } */
                    }
                } else if (this.item.type == 'digital') {
                    if (this.item.name != "" && this.item.description != "" && this.item.price != "") {
                        this.submitItem();
                    } else {
                        if (this.item.name == "") {
                            this.error.name = true;
                            this.error_message.name = 'Product title is required';
                        }
                        if (this.item.description == "") {
                            this.error.description = true;
                            this.error_message.description = 'Product description is required';
                        }
                        if (this.item.price == "") {
                            this.error.price = true;
                            this.error_message.price = 'Product price is required';
                        }
                    }
                }
            },
            submitItem() {
                var self = this;
                $.LoadingOverlay("show");
                axios({
                    method: 'post',
                    url: '/api/items/store',
                    data: self.item
                }).then(response => {
                    if (response.data.status == 1) {
                        this.item.id = response.data.data.id;
                        this.item.sku = response.data.data.sku;
                        for(var i in this.error){
                            this.error[i] = false;
                        }
                        this.dropzone.processQueue();
                        $.LoadingOverlay("hide");
                    } else {
                        if(Helpers.isAssoc(response.data.errors) > 0){
                            for(let field in response.data.errors){
                                this.error_message[field] = response.data.errors[field][0];
                                this.error[field] = true;
                            }
                            swal("Error!", 'Required fields missing', "error");
                        }else{
                            swal('Error', response.data.errors[0], 'error');
                        }
                        $.LoadingOverlay("hide");
                    }
                }).catch((error) => {
                    for(var i in this.error){
                        this.error[i] = false;
                    }
                    if(Helpers.isAssoc(error.response.data.errors) > 0){
                        for(let field in error.response.data.errors){
                            this.error_message[field] = error.response.data.errors[field][0];
                            this.error[field] = true;
                        }
                        swal("Error!", 'Required fields missing', "error");
                    }else{
                        swal('Error', error.response.data.errors[0], 'error');
                    }
                    $.LoadingOverlay("hide");
                });
            },

            updateItem(id) {
                if (this.item.type == 'physical') {
                    if (this.item.name != "" && this.item.description != "" && this.item.total_qty != "" && this.item.price != ""
                        && this.item.height != "" && this.item.weight != "" && this.item.length != "" && this.item.width != "") {
                        this.submitUpdateItem(id);
                    } else {
                        if (this.item.name == "") {
                            this.error.name = true;
                            this.error_message.name = 'Product title is required';
                        }
                        if (this.item.description == "") {
                            this.error.description = true;
                            this.error_message.description = 'Product description is required';
                        }
                        /*      if (this.item.upc == "") {
                                 this.error.upc = true;
                                 this.error_message.upc = 'Universal product code is required';
                             } */
                        if (this.item.price == "") {
                            this.error.price = true;
                            this.error_message.price = 'Product price is required';
                        }
                        if (this.item.total_qty == "") {
                            this.error.total_qty = true;
                            this.error_message.total_qty = 'Total quantity is required';
                        }
                        if (this.item.height == "") {
                            this.error.height = true;
                            this.error_message.height = 'Product height  is required';
                        }
                        if (this.item.weight == "") {
                            this.error.weight = true;
                            this.error_message.weight = 'Product weight  is required';
                        }
                        if (this.item.length == "") {
                            this.error.length = true;
                            this.error_message.length = 'Product length  is required';
                        }
                        if (this.item.width == "") {
                            this.error.width = true;
                            this.error_message.width = 'Product width  is required';
                        }
                        /*  if (this.dropzone.files.length == 0) {
                             this.error.picture = true;
                             this.error_message.picture = 'At least one picture about this product is required';
                         } */
                    }
                } else if (this.item.type == 'digital') {
                    if (this.item.name != "" && this.item.description != "" && this.item.price != "") {
                        this.submitUpdateItem(id);
                    } else {
                        if (this.item.name == "") {
                            this.error.name = true;
                            this.error_message.name = 'Product title is required';
                        }
                        if (this.item.description == "") {
                            this.error.description = true;
                            this.error_message.description = 'Product description is required';
                        }
                        if (this.item.price == "") {
                            this.error.price = true;
                            this.error_message.price = 'Product price is required';
                        }
                    }
                }
            },
            submitUpdateItem(id) {
                var self = this;
                $.LoadingOverlay("show");
                axios({
                    method: 'put',
                    url: '/api/items/update/' + id,
                    data: self.item
                }).then(response => {
                    $.LoadingOverlay("hide");
                    if (response.data.status == 1) {
                        for(var i in this.error){
                            this.error[i] = false;
                        }
                        toastr.success('The items has been successfully updated', 'Item updated.', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 3000 });
                        this.item.id = response.data.data.id;
                        this.item.sku = response.data.data.sku;
                        this.dropzone.processQueue();
                    } else {
                        if(Helpers.isAssoc(response.data.errors) > 0){
                            for(let field in response.data.errors){
                                this.error_message[field] = response.data.errors[field][0];
                                this.error[field] = true;
                            }
                            swal("Error!", 'Required fields missing', "error");
                        }else{
                            swal('Error', response.data.errors[0], 'error');
                        }
                    }
                }).catch((error) => {
                    for(var i in this.error){
                        this.error[i] = false;
                    }
                    if(Helpers.isAssoc(error.response.data.errors) > 0){
                        for(let field in error.response.data.errors){
                            this.error_message[field] = error.response.data.errors[field][0];
                            this.error[field] = true;
                        }
                        swal("Error!", 'Required fields missing', "error");
                    }else{
                        swal('Error', error.response.data.errors[0], 'error');
                    }
                    $.LoadingOverlay("hide");
                });
            },
            setWeightUnit(w_u) {
                this.item.weight_unit = w_u;
            },
            setLengthUnit(l_u, unit) {
                this.item.length_unit = l_u;
            },
            setHeightUnit(h_u) {
                this.item.height_unit = h_u;
            },
            setWidthUnit(w_u) {
                this.item.width_unit = w_u;
            },
            setBoxUnit(b_u) {
                this.item.box_unit = b_u;
            },
            setcategory(category) {
                this.item.categories.push(category);
            },
            syncWithAmazon() {
                if (this.amazon.asin != '') {
                    var self = this;
                    var data = {
                        marketplace_id: self.amazon.asin,
                        item_id: this.item.id,
                        marketplace: 'amazon'
                    }
                    $.LoadingOverlay("show");
                    axios({
                        method: 'post',
                        url: '/api/items/marketplace-link',
                        data: data
                    }).then(response => {
                        $.LoadingOverlay("hide");
                        if (response.data.status == 1) {
                            this.item.marketplaceItems.push(response.data.data);
                            $('#syncAmazon').modal('hide');
                            toastr.success('Item synced in marketplace: <b>' + data.marketplace + '</b>', 'Item successfully synced', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 3000 });
                        } else {
                            this.error.asin = true;
                            this.error_message.asin = response.data.errors[0];
                        }
                    }).catch((error) => {
                        $.LoadingOverlay("hide");
                        if (error.response.data.errors.marketplace_id.length > 0) {
                            if (data.marketplace == 'amazon') {
                                this.error_message.asin = 'ASIN format is invalid';
                            } else {
                                this.error_message.asin = error.response.data.errors.marketplace_id[0];
                            }
                        }
                        this.error.asin = true;
                    });

                } else {
                    this.error.asin = true;
                    this.error_message.asin = 'ASIN is required';
                }
            },

            removeSync(id, marketplace) {
                swal({
                    title: "Remove element link",
                    text: "This action will remove the link between this item and its counterpart in the marketplace: " + marketplace,
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                }).then((confirm) => {
                    if (confirm) {
                        $.LoadingOverlay("show");
                        axios({
                            method: 'delete',
                            url: '/api/items/marketplace-remove/' + id,
                        }).then(response => {
                            $.LoadingOverlay("hide");
                            if (response.data.status == 1) {
                                if (this.item.marketplaceItems.length > 0) {
                                    for (var i in this.item.marketplaceItems) {
                                        if (this.item.marketplaceItems[i].id == response.data.data.id) {
                                            this.item.marketplaceItems.splice(i, 1);
                                        }
                                    }
                                }
                            } else {
                                this.error.asin = true;
                                this.error_message.asin = response.data.errors[0];
                            }
                        }).catch((error) => {
                            swal("Error!", error, "error");
                        });
                    }
                });
            },

            alertQuantityChange() {
                var el = document.createElement('span'),
                    t = document.createTextNode("It looks that you are about to update the available quantity of this product. In order to keep this quantity synced with the linked marketplaces, you must ensure that the local SKU value should be the same on each linked marketplace. Note that some marketplaces does not reflet the change immediatly.");
                el.style.cssText = 'color:#898b8d';
                el.appendChild(t);
                swal({
                    title: "Item quantity change",
                    content: {
                        element: el
                    },
                    icon: "warning"
                });
            }
        },
        watch: {
            'package_units'(val) {
                if (val != '') {
                    this.error.package_units = false;
                    this.error_message.package_units = '';
                }
            },
            'amazon.asin'(val) {
                if (val != '') {
                    this.error.asin = false;
                    this.error_message.asin = '';
                }
            },
            'item.name'(val) {
                if (val != '') {
                    this.error.name = false;
                    this.error_message.name = '';
                }
            },
            'item.description'(val) {
                if (val != '') {
                    this.error.description = false;
                    this.error_message.description = '';
                }
            },
            'item.upc'(val) {
                if (val != '') {
                    this.error.upc = false;
                    this.error_message.upc = '';
                }
            },
            'item.total_qty'(val) {
                if (val != '') {
                    this.error.total_qty = false;
                    this.error_message.total_qty = '';
                }
                if (val != this.previos_qty && val != '' && this.item.marketplaceItems.length > 0) {
                    this.alertQuantityChange();
                }
            },
            'item.price'(val) {
                if (val != '') {
                    this.error.price = false;
                    this.error_message.price = '';
                }
            },
            'item.length'(val) {
                if (val != '') {
                    this.error.length = false;
                    this.error_message.length = '';
                }
            },
            'item.weight'(val) {
                if (val != '') {
                    this.error.weight = false;
                    this.error_message.weight = '';
                }
            },
            'item.height'(val) {
                if (val != '') {
                    this.error.height = false;
                    this.error_message.height = '';
                }
            },
            'item.width'(val) {
                if (val != '') {
                    this.error.width = false;
                    this.error_message.width = '';
                }
            }
        },
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
            }
        },
        created() {
            if (this.editable_item) {
                this.item.id = this.editable_item.id ? this.editable_item.id : '';
                this.item.upc = this.editable_item.upc ? this.editable_item.upc : '';
                this.item.sku = this.editable_item.sku ? this.editable_item.sku : '';
                this.item.type = this.editable_item.type;
                this.item.name = this.editable_item.name ? this.editable_item.name : '';
                this.item.description = this.editable_item.description ? this.editable_item.description : '';
                this.item.price = this.editable_item.price ? this.editable_item.price : '';
                this.item.msrp = this.editable_item.msrp ? this.editable_item.msrp : '';
                this.item.total_qty = this.editable_item.total_qty ? this.editable_item.total_qty : '';
                this.previos_qty = this.editable_item.total_qty ? this.editable_item.total_qty : '';
                this.item.weight = this.editable_item.weight ? this.editable_item.weight : '';
                this.item.weight_unit = this.editable_item.weight_unit ? this.editable_item.weight_unit : '';
                this.item.length = this.editable_item.length ? this.editable_item.length : '';
                this.item.length_unit = this.editable_item.length_unit ? this.editable_item.length_unit : '';
                this.item.width = this.editable_item.width ? this.editable_item.width : '';
                this.item.width_unit = this.editable_item.width_unit ? this.editable_item.width_unit : '';
                this.item.height = this.editable_item.height ? this.editable_item.height : '';
                this.item.height_unit = this.editable_item.height_unit ? this.editable_item.height_unit : '';
                // this.item.units_in_box = this.editable_item.units_in_box ? this.editable_item.units_in_box : '';
                // this.item.box_unit = this.editable_item.box_unit ? this.editable_item.box_unit : '';
                this.item.package_type = this.editable_item.package_type ? this.editable_item.package_type : [];
                this.item.options = this.editable_item.options ? this.editable_item.options : [];
                this.item.variants = this.editable_item.variants ? this.editable_item.variants : [];
                this.item.collections = this.editable_item.collections ? this.editable_item.collections : [];
                this.item.cost = this.editable_item.cost ? this.editable_item.cost : '';
                this.item.discount_percent = this.editable_item.discount_percent ? this.editable_item.discount_percent : '';
                this.item.um = this.editable_item.um ? this.editable_item.um : '';
                this.item.category_id = this.editable_item.category_id ? this.editable_item.category_id : '';
                this.item.tax_percent = this.editable_item.tax_percent ? this.editable_item.tax_percent : '';
                if (this.editable_item.marketplace_items) {
                    this.item.marketplaceItems = this.editable_item.marketplace_items.length > 0 ? this.editable_item.marketplace_items : [];
                }
            }
            console.log(this.item);
        },
        mounted() {
            this.$nextTick(function () {
                var self = this;
                $("#select2-category").select2();
                $("#select2-type").select2();
                this.publish_datepicker = $('#publish').datetimepicker({
                    format: 'MM/DD/YYYY hh:mm A'
                }).on('dp.change', function (e) {
                    self.item.publish = e.date.format('MM/DD/YYYY hh:mm A');
                });

                if (this.editable_item) {
                    this.publish_datepicker.data("DateTimePicker").date(moment(this.editable_item.publish_date).format('MM/DD/YYYY hh:mm A'));
                }

                this.dropzone = Dropzone.options.dpzMultipleFiles = {
                    url: "/api/files/upload",
                    paramName: "picture",
                    maxFilesize: 6.0,
                    clickable: true,
                    autoProcessQueue: false,
                    parallelUploads: 2,
                    addRemoveLinks: true,
                    dictRemoveFile: " Remove",

                    sending: function (file, xhr, formData) {
                        formData.append("_token", document.head.querySelector('meta[name="csrf-token"]').content);
                        formData.append("owner_id", self.item.id);
                        formData.append("owner_type", "item");
                    },

                    success: function (file, response) {
                        file.id = response.data.id;
                    },

                    removedfile: function (file) {
                        if (self.item.id) {
                            axios({
                                method: 'delete',
                                url: '/api/files/destroy/' + file.id
                            }).then(response => {
                                if (response.data.status == 1) {
                                    file.previewElement.remove();
                                } else {
                                    swal("Error!", response.data.errors[0], "error");
                                }
                            }).catch((error) => {
                                swal("Error!", error, "error");
                            });
                        } else {
                            file.previewElement.remove();
                        }
                    },

                    complete: function (file, xhr, formData) {
                        $.LoadingOverlay("hide");
                        if (file.accepted) {
                            // window.location.href = '/admin/store/items';
                        }
                    },

                    init: function (file) {
                        self.dropzone = Dropzone.forElement("#dpz-multiple-files");
                        this.on("success", function () {
                            self.dropzone.options.autoProcessQueue = true;
                        });

                        if (self.editable_item) {
                            if (self.editable_item.files.length > 0) {
                                for (var i in self.editable_item.files) {
                                    var mockFile = {
                                        id: self.editable_item.files[i].id,
                                        name: self.editable_item.files[i].name,
                                        size: 12345,
                                        type: 'image/jpeg'
                                    };
                                    this.emit('addedfile', mockFile);
                                    this.emit('thumbnail', mockFile, '/storage/' + self.editable_item.files[i].thumb_path);
                                    mockFile.previewElement.classList.add('dz-success');
                                    mockFile.previewElement.classList.add('dz-complete');
                                }
                            }
                        }
                        this.on("thumbnail", function (file) {
                            if (file.accepted !== false) {
                                var minImageWidth = 640;
                                var maxImageWidth = 5000;
                                var minImageHeight = 480;
                                var maxImageHeight = 5000;
                                if (file.width >= maxImageWidth || file.width <= minImageWidth || file.height >= maxImageHeight || file.height <= minImageHeight) {
                                    file.rejectDimensions();
                                }
                                else {
                                    file.acceptDimensions();
                                }
                            }

                            self.error.picture = false;
                            self.error_message.picture = '';
                        });

                        this.on("error", function (file, error) {
                            if (!file.accepted) {
                                this.removeFile(file);
                                toastr.error(error, 'Picture upload error.', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 3000 });
                            }
                        });

                    },
                    accept: function (file, done) {
                        file.acceptDimensions = done;
                        file.rejectDimensions = function () { done("Invalid dimension. Min file size : 1024x768. Max file size: 1600x1200"); };
                    }
                }

                $('#select2-category').on('change', function (e) {
                    self.item.category_id = parseInt($('#select2-category').select2('data')[0].id);
                });

                $('#select2-type').on('change', function (e) {
                    self.item.type = $('#select2-type').select2('data')[0].id;
                });

                $('#select2-type').on('change', function (e) {
                    self.item.type = $('#select2-type').select2('data')[0].id;
                });

                self.item.taxable = self.editable_item.taxable;

                if (self.item.taxable == 1) {
                    $('#taxable').iCheck('check');
                } else {
                    $('#taxable').iCheck('uncheckeck');
                }

                self.item.active = self.editable_item.active;

                if (self.item.active == 1) {
                    $('#active').iCheck('check');
                } else {
                    $('#active').iCheck('uncheckeck');
                }

                $('.skin-square input').iCheck({
                    checkboxClass: 'icheckbox_square-red',
                    radioClass: 'iradio_square-red',
                });

                $('#taxable').on('ifChecked', function (event) {
                    self.item.taxable = 1;
                });

                $('#taxable').on('ifUnchecked', function (event) {
                    self.item.taxable = 0;
                    self.item.tax_percent = '';
                });

                $('#active').on('ifChecked', function (event) {
                    self.item.active = 1;
                });

                $('#active').on('ifUnchecked', function (event) {
                    self.item.active = 0;
                });

                $('#syncAmazon').on('hidden.bs.modal', function () {
                    self.amazon.asin = "";
                    self.error.asin = false;
                });

                $("#select2-packages").select2({
                    placeholder: "Select packages",
                    ajax: {
                        url: '/api/admin/packages/search',
                        dataType: 'json',
                        delay: 250,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        data: function (params) {
                            params.term = (typeof params.term == 'undefined') ? '' : params.term;
                            return {
                                query: params.term + '*', // search term
                                fields: ['id', 'name'],
                                page: params.page,
                                limit: 5
                            };
                        },
                        processResults: function (response, params) {
                            params.page = params.page || 1;
                            return {
                                results: response.data,
                                pagination: {
                                    more: (params.page) < response.pagination.total_pages
                                }
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) { return markup; }, 
                    templateResult: function (repo) {
                        if (repo.loading) return repo.text;
                        var markup = "<div class='select2-result-repository clearfix'>" + repo.name + "</div>";
                        return markup;
                    }, 
                    templateSelection: function (repo) {
                        if (repo.name) {
                            self.package_id = repo.id;
                            self.package_name = repo.name;
                            return repo.name
                        } else {
                            return repo.text;
                        }
                    }
                });
                $("#select2-options").select2({
                    placeholder: "Select options",
                    ajax: {
                        url: '/api/admin/options/search',
                        dataType: 'json',
                        delay: 250,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        data: function (params) {
                            params.term = (typeof params.term == 'undefined') ? '' : params.term;
                            return {
                                query: params.term + '*', // search term
                                fields: ['id', 'label'],
                                page: params.page,
                                limit: 5
                            };
                        },
                        processResults: function (response, params) {
                            params.page = params.page || 1;
                            return {
                                results: response.data,
                                pagination: {
                                    more: (params.page) < response.pagination.total_pages
                                }
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) { return markup; }, 
                    templateResult: function (repo) {
                        if (repo.loading) return repo.text;
                        var markup = "<div class='select2-result-repository clearfix'>" + repo.label + "</div>";
                        return markup;
                    }, 
                    templateSelection: function (repo) {
                        if (repo.label) {
                            self.option = {
                                id: repo.id,
                                label: repo.label
                            }
                            return repo.label
                        } else {
                            return repo.text;
                        }
                    }
                });
                $("#select2-collections").select2({
                    placeholder: "Select collections",
                    ajax: {
                        url: '/api/collections/search',
                        dataType: 'json',
                        delay: 250,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        data: function (params) {
                            params.term = (typeof params.term == 'undefined') ? '' : params.term;
                            return {
                                query: params.term + '*', // search term
                                fields: ['id', 'name'],
                                page: params.page,
                                limit: 5
                            };
                        },
                        processResults: function (response, params) {
                            params.page = params.page || 1;
                            return {
                                results: response.data,
                                pagination: {
                                    more: (params.page) < response.pagination.total_pages
                                }
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) { return markup; }, 
                    templateResult: function (repo) {
                        if (repo.loading) return repo.text;
                        var markup = "<div class='select2-result-repository clearfix'>" + repo.name + "</div>";
                        return markup;
                    }, 
                    templateSelection: function (repo) {
                        if (repo.name) {
                            self.collection = {
                                id: repo.id,
                                name: repo.name
                            }
                            return repo.name
                        } else {
                            return repo.text;
                        }
                    }
                });

                $("#units-package").on('hide.bs.modal', function(){
                    self.package_units = '';
                })
                $("#collections-modal").on('hide.bs.modal', function(){
                   $('#select2-collections').val(null).trigger('change');
                })
                $("#options-modal").on('hide.bs.modal', function(){
                   $('#select2-options').val(null).trigger('change');
                })
            });
        }
    }
</script>
<style>
    form .form-section {
        border-bottom: 1px solid #eceff2;
    }

    .dz-image>img {
        width: 120px !important;
        height: auto !important;
    }

    .input-error-select {
        color: #d61212;
        border: 1px solid #b60707;
        border-radius: 5px
    }

    .error-dropzone {
        border: 2px dashed #b60707 !important;
    }

    .message-error {
        color: #d61212;
        float: right;
        padding-top: 10px;
        font-size: 12px;
    }

    .swal-text {
        text-align: center !important;
    }

    .dropdown-item:focus,
    .dropdown-item:hover {
        background-color: #fff;
        color: #000;
    }
</style>