trigger MatchedItems_OpportunityChange on Opportunity (after insert, after update) {

    List <Matched_Items__c> matchedItemList = new List <Matched_Items__c>();
    Map <Id, Matched_Items__c> oldMatchedItemsMap = new Map<Id, Matched_Items__c>();

    for (Opportunity o : Trigger.new) {
        system.debug ('-----START');

        String select_sql = 'SELECT Id FROM Product2 WHERE ';
        String where_sql = '';

        if ( integer.valueOf(o.Budget_range_from__c) > 0 ) {
            if ( where_sql != '' ) where_sql += ' AND ';
            where_sql += ' AskingPrice__c > 0 AND AskingPrice__c >= ' + integer.valueOf(o.Budget_range_from__c);
        }
        system.debug ('-----Budget_range_from__c');

        if ( integer.valueOf(o.Budget_range_to__c) > 0 ) {
            if ( where_sql != '' ) where_sql += ' AND ';
            where_sql += ' AskingPrice__c > 0 AND AskingPrice__c <= ' + integer.valueOf(o.Budget_range_to__c);
        }
        system.debug ('-----Budget_range_to__c');

        if( o.Year_From__c != null && integer.valueof( o.Year_From__c ) > 0 ) {
            if ( where_sql != '' ) where_sql += ' AND ';
            where_sql += ' ModelYear__c>\'0\' AND ModelYear__c>=\'' + integer.valueof( o.Year_From__c ) + '\' AND YearBuilt__c>\'0\' AND YearBuilt__c>=\'' + integer.valueof( o.Year_From__c ) + '\'';
        }
        system.debug ('-----Year_From__c');

        if( o.Year_To__c != null && integer.valueof( o.Year_To__c ) > 0 ) {
            if ( where_sql != '' ) where_sql += ' AND ';
            where_sql += ' ModelYear__c>\'0\' AND ModelYear__c<=\'' + integer.valueof( o.Year_To__c ) + '\' AND YearBuilt__c>\'0\' AND YearBuilt__c<=\'' + integer.valueof( o.Year_To__c ) + '\'';
        }
        system.debug ('-----Year_To__c');

        if( o.Vessel_Name__c != null ) {
            if ( where_sql != '' ) where_sql += ' AND ';
            where_sql += ' Name LIKE \'%' + String.escapeSingleQuotes( o.Vessel_Name__c ) + '%\'';
        }
        system.debug ('-----Vessel_Name__c');

        if( o.Builder__c != null ) {
            if ( where_sql != '' ) where_sql += ' AND ';
            where_sql += ' Builder__c LIKE \'%' + String.escapeSingleQuotes( o.Builder__c ) + '%\'';
        }
        system.debug ('-----Builder__c');

        if( o.Vessel_Type__c != null ) {
            if ( where_sql != '' ) where_sql += ' AND ';
            where_sql += ' VesselType__c = \'' + o.Vessel_Type__c + '\'';
        }
        system.debug ('-----VesselType__c');

        if( o.Listing_Type__c != null ) {
            if ( where_sql != '' ) where_sql += ' AND ';

            if ( o.Listing_Type__c == 'For Sale' ) {
                where_sql += ' For_Sale__c = ' + true;
            } else {
                where_sql += ' For_Charter__c = ' + true;
            }
        }
        system.debug ('-----Listing_Type__c');

        if ( o.LOA_Type__c != null ) {

            String LOAFieldName = '';

            if ( o.LOA_Type__c == 'Feet' ) {
                LOAFieldName = 'LOAFeet__c';
            } else {
                LOAFieldName = 'LOAMeters__c';
            }

            if ( o.LOA_From__c != null ) {
                if ( where_sql != '' ) where_sql += ' AND ';
                where_sql += ' ' + LOAFieldName + ' > 0 AND ' + LOAFieldName + ' >= ' + o.LOA_From__c;
            }
            if ( o.LOA_To__c != null ) {
                if ( where_sql != '' ) where_sql += ' AND ';
                where_sql += ' ' + LOAFieldName + ' > 0 AND ' + LOAFieldName + ' <= ' + o.LOA_To__c;
            }
        }
        system.debug ('-----LOA_Type__c');

        if( o.LocationRegionName__c != null ) {
            if ( where_sql != '' ) where_sql += ' AND ';
            where_sql += ' LocationRegionName__c LIKE \'%' + String.escapeSingleQuotes( o.LocationRegionName__c ) + '%\'';
        }
        system.debug ('-----LocationRegionName__c');

        system.debug ( 'WHERE => ' + select_sql + where_sql );

        if ( where_sql != '' ) {
            List <Product2> mProducts = Database.query( select_sql + where_sql );

            List <Matched_Items__c> oldMatchedItemsList = [ SELECT Product__c FROM Matched_Items__c WHERE Opportunity__c = :o.Id ];
            for (Matched_Items__c mi: oldMatchedItemsList) {
                oldMatchedItemsMap.put(mi.Product__c, mi);
            }

            for(Product2 p: mProducts) {
                system.debug ('-----Found product id:' + p.Id);

                // add only if such record not exists
                List <Matched_Items__c> isMatchedItemExists = new List<Matched_Items__c>();
                isMatchedItemExists = [ SELECT Id FROM Matched_Items__c WHERE Opportunity__c = :o.Id AND Product__c = :p.Id ];

                if( oldMatchedItemsMap.containsKey(p.Id) ){
                    oldMatchedItemsMap.remove(p.Id);
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
    } catch (system.Dmlexception e) {
        system.debug (e);
    }
}