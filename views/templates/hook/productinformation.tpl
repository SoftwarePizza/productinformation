{if !isset($product.grouped_features)}{$product.grouped_features = $product.features}{/if}
{if $product.grouped_features}
  <section class="product-features">
      {foreach from=$product.grouped_features item=feature}
        {foreach from=$productinformation item=prodinf}
          {if $feature.name==$prodinf.name && $prodinf.active}      
          {if $feature.value|strstr:{$prodinf.value}}
            <div>
            	<div style="margin-bottom: 10px;"> <img src="{$urls.base_url}upload/productinformation/{$prodinf.image}" width="25px" style="margin-right:5px; margin-top: -2px"/>{$prodinf.content} </div>
            </div>
            {/if}
          {/if}
        {/foreach}
      {/foreach}
  </section>
{/if}
