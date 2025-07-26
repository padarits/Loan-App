/*public static function  updateAmount($session_guid)*/
SELECT
	t1.entry_value,
  t4.entry_value as t4_entry_value
, t5.entry_value as t5_entry_value
, t6.entry_value as t6_entry_value
	FROM public.horizon_api_data t1
	--left join public.horizon_api_data t3 on t3.parent_guid = t1.guid 
	left join public.horizon_api_data t4 on t4.parent_guid = t1.parent_guid and t4.entry_key = 'totalAmount'
	left join public.horizon_api_data t5 on t5.parent_guid = t1.parent_guid and t5.entry_key = 'totalAmountCurrencyCode'
	left join public.horizon_api_data t6 on t6.parent_guid = t1.parent_guid and t6.entry_key = 'companyName'
	where t1.entry_path LIKE '/%/companyId' and t1.entry_key = 'companyId' and cast(t4.entry_value AS decimal) > 0
	;

    /*public static function  fillFromHorizonApiData($session_guid){*/
SELECT 
  t4.entry_value as t4_entry_value
, t5.entry_value as t5_entry_value
, t7.entry_value as t7_entry_value
, t9.entry_value as t9_entry_value
	FROM public.horizon_api_data t1
	left join public.horizon_api_data t2 on t2.parent_guid = t1.guid --and t2.entry_path like '/collection/row/%'
	left join public.horizon_api_data t3 on t3.parent_guid = t2.guid and t3.entry_key = 'K'
	left join public.horizon_api_data t4 on t4.parent_guid = t3.guid and t4.entry_key = 'NOSAUK'
	left join public.horizon_api_data t5 on t5.parent_guid = t3.guid and t5.entry_key = 'REG_NR'
	left join public.horizon_api_data t6 on t6.parent_guid = t3.guid and t6.entry_key = 'DNV'
	left join public.horizon_api_data t7 on t7.parent_guid = t6.guid and t7.entry_key = 'SUMMA_DB_PV'
	left join public.horizon_api_data t8 on t8.parent_guid = t6.guid and t8.entry_key = 'PK_VAL'
	left join public.horizon_api_data t9 on t9.parent_guid = t8.guid and t9.entry_key = 'href'
	where t1.entry_path = '/collection/row' and t1.entry_key = 'row'
	;

/*public static function  fillFromInsuranceApiData($session_guid, InsuranceData &$insuranceDataRecord)*/
SELECT 
  t4.entry_value as t4_entry_value
, t7.entry_value as t7_entry_value
	FROM public.horizon_api_data t1
	left join public.horizon_api_data t2 on t2.parent_guid = t1.guid 
	left join public.horizon_api_data t3 on t3.parent_guid = t2.guid --and t3.entry_key = '/results/%'
	left join public.horizon_api_data t4 on t4.parent_guid = t3.guid and t4.entry_key = 'companyId'
	left join public.horizon_api_data t5 on t5.parent_guid = t4.guid --and t5.entry_key = 'companyId'
	left join public.horizon_api_data t6 on t6.parent_guid = t3.guid and t6.entry_key = 'legalData'
	left join public.horizon_api_data t7 on t7.parent_guid = t6.guid and t7.entry_key = 'companyName'
	where t1.entry_path = '/results' and t4.entry_key = 'companyId'
	;


