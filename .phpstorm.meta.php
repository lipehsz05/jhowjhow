<?php

namespace PHPSTORM_META {
    // This helps IDE recognize Laravel's facades
    override(\Illuminate\Support\Facades\App::make(0), map([
        '' => '@',
    ]));
    
    override(\app(0), map([
        '' => '@',
    ]));
    
    // Help IDE recognize Laravel's helpers return types
    expectedReturnValues(\Auth::id(), 1);
    expectedReturnValues(\DB::beginTransaction(), true);
    expectedReturnValues(\DB::commit(), true);
    expectedReturnValues(\DB::rollBack(), true);
    
    // Override for Eloquent models
    override(\App\Models\MovimentacaoEstoque::findOrFail(0), map([
        '' => '@'
    ]));
    
    override(\App\Models\Produto::findOrFail(0), map([
        '' => '@'
    ]));
}
