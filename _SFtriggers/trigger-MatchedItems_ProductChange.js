trigger MatchedItems_ProductChange on Product2 (after insert, after update) {

    List <Matched_Items__c> matchedItemList = new List <Matched_Items__c>();
    Map <Id, Matched_Items__c> oldMatchedItemsMap = new Map<Id, Matched_Items__c>();
    
    for (Product2 p : Trigger.new) {
        system.debug ('-----START');
    
        String select_sql = 'SELECT Id FROM Opportunity WHERE ';
        String where_sql = '';
    
        if( integer.valueOf(p.AskingPrice__c) > 0 ) {
            if ( where_sql != '' ) where_sql += ' AND ';
            where_sql = ' (( Budget_range_from__c = null OR Budget_range_from__c <=' + integer.valueOf(p.AskingPrice__c) + ') '
                        + 'AND ( Budget_range_to__c = null OR Budget_range_to__c >=' + integer.valueOf(p.AskingPrice__c) + ' ))';
        }
        system.debug ('-----AskingPrice__c');

        if( p.ModelYear__c != null && integer.valueOf(p.ModelYear__c) > 0 ) {
            if ( where_sql != '' ) where_sql += ' AND ';
            where_sql += ' (( Year_From__c = null OR Year_From__c <= \'' + integer.valueOf(p.ModelYear__c) + '\') '
                + ' AND ( Year_To__c = null OR Year_To__c >= \'' + integer.valueOf(p.ModelYear__c) + '\'))';
        }
        system.debug ('-----ModelYear__c');

        if( p.YearBuilt__c != null && integer.valueOf(p.YearBuilt__c) > 0 ) {
            if ( where_sql != '' ) where_sql += ' AND ';
            where_sql += ' (( Year_From__c = null OR Year_From__c <= \'' + integer.valueOf(p.YearBuilt__c) + '\') '
                + ' AND ( Year_To__c = null OR Year_To__c >= \'' + integer.valueOf(p.YearBuilt__c) + '\'))';
        }
        system.debug ('-----YearBuilt__c');

        if( p.Id != null ) {
            if ( where_sql != '' ) where_sql += ' AND ';
            where_sql += ' ( Vessels__c = \'' + p.Id + '\' OR Vessels__c = null )';
        }  
        system.debug ('-----Id');

        if( p.Builders__c != null ) {
            if ( where_sql != '' ) where_sql += ' AND ';
            where_sql += ' ( Builders__c = \'' + p.Builders__c + '\' OR Builders__c = null )';
        }
        system.debug ('-----Builders__c');

        if( p.VesselType__c != null ) {
            if ( where_sql != '' ) where_sql += ' AND ';
            where_sql += ' ( Vessel_Type__c = \'' + p.VesselType__c + '\' OR Vessel_Type__c = null )';
        }
        system.debug ('-----VesselType__c');
        
        if( p.For_Sale__c == true ) {
            if ( where_sql != '' ) where_sql += ' AND ';
            where_sql += ' ( Listing_Type__c = \'For Sale\' OR Listing_Type__c = null )';
        }
        system.debug ('-----For_Sale__c');

        if( p.LOAMeters__c != null || p.LOAFeet__c != null ) {
            where_sql += ' AND ( ';

            if( p.LOAMeters__c != null ) {
                where_sql += ' LOA_Type__c = null OR ( LOA_Type__c = \'Meters\' AND ( ( LOA_From__c > 0 AND LOA_From__c <=' + p.LOAMeters__c + ' ) OR ( LOA_To__c > 0 AND LOA_To__c >=' + p.LOAMeters__c + ' ) ) ) ';
            }

            if( p.LOAFeet__c != null ) {
                if ( p.LOAMeters__c != null ) where_sql += ' OR ';
                where_sql += ' LOA_Type__c = null OR ( LOA_Type__c = \'Feet\' AND ( ( LOA_From__c > 0 AND LOA_From__c <=' + p.LOAFeet__c + ' ) OR ( LOA_To__c > 0 AND LOA_To__c >=' + p.LOAFeet__c + ' ) ) ) ';
            }
           
            where_sql += ' ) ';
        }
        system.debug ('-----LOAMeters__c');
        
        if( p.Regions__c != null ) {
            if ( where_sql != '' ) where_sql += ' AND ';
            where_sql += ' ( Location_Regions_Name__c = \'' + p.Regions__c + '\' OR Location_Regions_Name__c = null )';
        }
        system.debug ('-----Location_Regions_Name__c');
        
        system.debug ( 'WHERE => ' + select_sql + where_sql );
        
        if( where_sql != '' ) {
            List <Opportunity> opportunities = Database.query( select_sql + where_sql + ' LIMIT 50' );

            List <Matched_Items__c> oldMatchedItemsList = [ SELECT Opportunity__c FROM Matched_Items__c WHERE Product__c = :p.Id ];
            for (Matched_Items__c mi: oldMatchedItemsList) {
                oldMatchedItemsMap.put(mi.Opportunity__c, mi);
            }

            for (Opportunity o : opportunities) {

                system.debug ('-----Found opportunity id:' + o.Id);

                // add only if such record not exists
                List <Matched_Items__c> isMatchedItemExists = new List<Matched_Items__c>();
                isMatchedItemExists = [ SELECT Id FROM Matched_Items__c WHERE Product__c = :p.Id AND Opportunity__c = :o.Id ];

                if( oldMatchedItemsMap.containsKey(o.Id) ){
                    oldMatchedItemsMap.remove(o.Id);
                } else {
                    system.debug ('-----Adding');
                    Matched_Items__c matchedItem = new Matched_Items__c();
                    matchedItem.Opportunity__c = o.Id;
                    matchedItem.Product__c = p.Id;
                    matchedItemList.add(matchedItem);
                }
            }
        }
    }
    try {
        
        //remove not matched items
        delete oldMatchedItemsMap.values();

        if (matchedItemList.size()>0) {
            system.debug ('-----MATCHES:');
            system.debug (matchedItemList);
            insert matchedItemList;
        } else {
            system.debug ('-----NO_MATCHES');
        }
    } catch (System.Dmlexception e) {
        System.debug (e);
    }
}