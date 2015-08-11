@isTest
 private class YatcoTestSuite {

      static testMethod void verifyInsertProduct_positive() {
         
            createDummyOpportunity();

            Product2 p = new Product2();
            p.Name = 'test';
            p.AskingPrice__c = 1000000;
            p.ModelYear__c = '2005';
            p.YearBuilt__c = '2006';
            p.Builder__c = 'test';
            p.VesselType__c = 'Motorboat';
            p.For_Sale__c = true;
            p.LOAFeet__c = 35;
            p.LocationRegionName__c = 'Florida';
            
            try {
                insert p;
            } catch (system.Dmlexception e) {
                system.debug (e);
            }
      }

      static testMethod void verifyInsertOpportunity_positive() {
         
            createDummyProduct();

            Opportunity o = new Opportunity();
            o.Name = 'test';
            o.StageName = 'Prospecting';
            o.CloseDate = Date.today();
            o.Budget_range_from__c = 500000;
            o.Budget_range_to__c = 1500000;
            o.Year_From__c = '2005';
            o.Year_To__c = '2010';
            o.Vessel_Name__c = 'test';
            o.Builder__c = 'test';
            o.Vessel_Type__c = 'Motorboat';
            o.Listing_Type__c = 'For Sale';
            o.LOA_Type__c = 'Feet';
            o.LOA_From__c = 30;
            o.LOA_To__c = 50;
            o.LocationRegionName__c = 'Florida';
                
            
            
            Account a = new Account(Name='TESTacc');   
            insert a;
            
            Contact c = new Contact(LastName='Doe');   
            c.Account = a;
            insert c;
                               
            o.Contact_Name__c = c.Id;

            
            try {
                insert o;
            } catch (system.Dmlexception e) {
                system.debug (e);
            }
      }
      
      static testMethod void verifyUpdateLead_positive() {
            system.debug ('-------- START');         
            // create a Lead
            try {
                Lead lead=new Lead(LastName='Doe',FirstName='John',Company='Test');
                system.debug (lead);
                    insert lead;  
                                        
                Database.LeadConvert lc = new database.LeadConvert();
                lc.setLeadId(lead.id);
                lc.setDoNotCreateOpportunity(false);
                lc.setConvertedStatus('Qualified');
            
                Database.LeadConvertResult lcr = Database.convertLead(lc); 

            } catch (system.Dmlexception e) {
                system.debug (e);
            }
      }

      static void createDummyOpportunity () {

            Opportunity o = new Opportunity();
            o.Name = 'test';
            o.StageName = 'Prospecting';
            o.CloseDate = Date.today();
            o.Budget_range_from__c = 500000;
            o.Budget_range_to__c = 1500000;
            o.Year_From__c = '2005';
            o.Year_To__c = '2010';
            o.Vessel_Name__c = 'test';
            o.Builder__c = 'test';
            o.Vessel_Type__c = 'Motorboat';
            o.Listing_Type__c = 'For Sale';
            o.LOA_Type__c = 'Feet';
            o.LOA_From__c = 30;
            o.LOA_To__c = 50;
            o.LocationRegionName__c = 'Florida';
                
            
            
            Account a = new Account(Name='TESTacc');   
            insert a;
            
            Contact c = new Contact(LastName='Doe');   
            c.Account = a;
            insert c;
                               
            o.Contact_Name__c = c.Id;

            insert o;
      }

      static void createDummyProduct () {
            Product2 p = new Product2();
            p.Name = 'test';
            p.AskingPrice__c = 1000000;
            p.ModelYear__c = '2005';
            p.YearBuilt__c = '2006';
            p.Builder__c = 'test';
            p.VesselType__c = 'Motorboat';
            p.For_Sale__c = true;
            p.LOAFeet__c = 35;
            p.LocationRegionName__c = 'Florida';
            
            insert p;
      }

 }