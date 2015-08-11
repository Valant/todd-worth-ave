trigger MatchedItems_LeadUpdate on Lead (after update) {

    for(Lead lead:System.Trigger.new) {
    
          if (Lead.IsConverted) {
                  List <Opportunity> ops = [SELECT Id, 
                                              Budget_range_from__c, 
                                              Budget_range_to__c, 
                                              Year_From__c, 
                                              Year_To__c,
                                              Vessels__c,
                                              Vessel_Type__c, 
                                              Listing_Type__c, 
                                              LOA_Type__c, 
                                              LOA_From__c, 
                                              LOA_To__c,
                                              Builders__c,
                                              Location_Regions_Name__c
                                            FROM Opportunity WHERE id=:Lead.ConvertedOpportunityId];
                  
                  for( Opportunity o:ops ) {
                      o.Budget_range_from__c  = Lead.Budget_range_from__c;
                      o.Budget_range_to__c    = Lead.Budget_range_to__c;
                      o.Year_From__c          = Lead.Year_From__c;
                      o.Year_To__c            = Lead.Year_To__c;
                      o.Vessels__c        = Lead.Vessels_Name__c;
                      o.Vessel_Type__c        = Lead.Vessel_Type__c;
                      o.Listing_Type__c       = Lead.Listing_Type__c;
                      o.LOA_Type__c           = Lead.LOA_Type__c;
                      o.LOA_From__c           = Lead.LOA_From__c;
                      o.LOA_To__c             = Lead.LOA_To__c;
                      o.Builders__c             = Lead.Builders__c;
                      o.Location_Regions_Name__c = Lead.Locations_Region_Name__c;
                      update(o);
                  }
          }
    }
}