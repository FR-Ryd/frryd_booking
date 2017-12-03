/*
    New translations for admin panels (and other not translated texts)
    in FR Ryd booking system.
*/

-- Menu items
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("profile_menu_title",1,"Profile");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("profile_menu_title",2,"Profil");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("sessions_menu_title",1,"Session");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("sessions_menu_title",2,"Pass");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("items_menu_title",1,"Items");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("items_menu_title",2,"Föremål");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("users_menu_title",1,"Users");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("users_menu_title",2,"Användare");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("bookings_menu_title",1,"Bookings");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("bookings_menu_title",2,"Bokningar");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("language_menu_title",1,"Language");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("language_menu_title",2,"Språk");

-- Logged in as info on every page
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("logged_in_as",1,"Logged in as ");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("logged_in_as",2,"Inloggad som");

-- Contact box on the front page
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("contact_box",1,'<h2>Contact us</h2><p>If you any questions and/or suggesstions for the lending service, then contact us at <a href="mailto:intendent@frryd.se" style="color:white;line-height:1.8em;">intendent@frryd.se</a><br></p>If you have any suggestions or improvements for the webpage, then contact us at <a href="mailto:it@frryd.se" style="color:white;line-height:1.8em;">it@frryd.se.</a>');
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("contact_box",2,'<h2>Kontakta Oss</h2><p>Om du har några förslag gällande utlåningen kan du kontakta oss på <a href="mailto:intendent@frryd.se" style="color:white;line-height:1.8em;">intendent@frryd.se</a><br></p>Om du har några förslag gällande hemsidan kan du kontakta oss på <a href="mailto:it@frryd.se" style="color:white;line-height:1.8em;">it@frryd.se.</a>');

-- Instructions for sessions selection on session Page
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("session_select_instructions",1,"Click on a day with a session to get to that session menu.<br /> Click on a free day to add a session.");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("session_select_instructions",2,"Klicka på datumet för en dag med ett pass för att komma till dess pass-meny.<br /> Klicka på ett ledigt datum för att lägga till ett pass där.");

-- Color info for session page
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("color_info_green",1,"Green &nbsp;(To be lended out!)");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("color_info_green",2,"Grön &nbsp;(Skall Utlämnas!)");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("color_info_red",1,"Red &nbsp;(To be returned!)");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("color_info_red",2,"Röd &nbsp;&nbsp;(Skall Återlämnas!)");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("color_info_yellow",1,"Yellow &nbsp;(Done, has been returned or lended out!)");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("color_info_yellow",2,"Gul &nbsp;&nbsp;&nbsp;(Klart! Varan har Återlämnats eller Utlämnats)");

-- Misc text on session page
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("session_summary",1,"This is what you have to look forward to this session!");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("session_summary",2,"Detta har ni att se fram emot detta pass!");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("items_booked",1,"items booked from this session.");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("items_booked",2,"föremål bokade för utlåmning detta pass.");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("items_to_return",1,"items to be returned this session.");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("items_to_return",2,"föremål bokade ska lämnas åter detta pass.");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("lend_out_box",1,'<h2 >Pick Ups</h2><p><i>These are the items booked to be picked up this session.</i></p>');
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("lend_out_box",2,'<h2 >Utlämningar</h2><p><i>Här listas bokningar för föremål som bokats för att hämtas ut detta pass.</i></p>');

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("return_box",1,'<h2>Returns</h2><p><i>These are the items to be returned this session.</i></p>');
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("return_box",2,'<h2>Återlämningar</h2><p><i>Här listas bokningar för föremål som bokats för att återlämnas detta pass.</i></p>');

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("everything_out_box",1,'<h2>Everything lended out</h2><p><i>These are all items lended out but not yet returned.</i></p>');
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("everything_out_box",2,'<h2>Allt utlånat</h2><p><i>Här listas allt som lånats ut men inte lämnats tillbaka.</i></p>');

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("no_items_lended",1,"No items are lended out.");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("no_items_lended",2,"Inga föremål är utlåmnade.");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remove",1,"Remove");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remove",2,"Ta bort");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("add_booking",1,"Add booking");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("add_booking",2,"Lägg till bokning");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("between",1,"between");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("between",2,"mellan");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("and",1,"and");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("and",2,"och");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("comment",1,"comment");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("comment",2,"kommentar");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("update",1,"update");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("update",2,"uppdatera");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("lended_out",1,"Lended out");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("lended_out",2,"Utlämnad");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("returned",1,"Returned");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("returned",2,"Återlämnad");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("delayed",1,"Delayed");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("delayed",2,"Försenad");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("all_lended",1,"All lended out");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("all_lended",2,"Alla utlämnade");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("edit_booking",1,"Edit booking");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("edit_booking",2,"Redigera bokning");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("all_returned",1,"All returned");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("all_returned",2,"Alla återlämnade");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("removed",1,"removed");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("removed",2,"borttagen");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("created",1,"created");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("created",2,"skapat");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("session_remove_error",1,"You can not remove this session when there exists booking to or from it.\n Remove or change them first.\n");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("session_remove_error",2,"Du kan inte ta bort detta pass då det finns bokningar till eller från detta.\n Ta bort eller ändra dem först.\n");

-- Translations for user page
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("unbooked",1,"Unbooked");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("unbooked",2,"Avbokat");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("unbook_error",1,"Could not cancel booking, you have already picked up something!");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("unbook_error",2,"Kunde inte avboka, du har redan hämtat någonting!");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("user",1,"user");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("user",2,"användare");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("liuid_error",1,"Invalid LiU ID. XXXXXDDD is accepted, where X is a letter and D is a digit.");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("liuid_error",2,"Ogiltigt Liu ID. XXXXXDDD accepteras, där X är en bokstav och D en siffra.");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remark_added",1,"Remark added!");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remark_added",2,"Anmärkning tillagd!");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remark_error",1,"The remark field can not be empty!");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remark_error",2,"Anmärknings fältet kan inte vara tom!");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remark_removed",1,"Chosen remark has been removed");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remark_removed",2,"Valda anmärkningarna är borttagna!");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remark_removed_error",1,"No remark selected");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remark_removed_error",2,"Du har inte valt någon anmärkning");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("to",1,"To");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("to",2,"Till");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("new",1,"new");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("new",2,"ny");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("user_management",1,"User Management");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("user_management",2,"Användarhantering");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("user_information",1,"User Information");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("user_information",2,"Användarinformation");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("update_information",1,"Update Information");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("update_information",2,"Uppdatera Information");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("delete_user",1,"Delete User");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("delete_user",2,"Ta bort användare");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remove_remark",1,"Remove Remark");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remove_remark",2,"Ta bort kommentar");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remarks",1,"Remarks");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("Remarks",2,"Kommentarer");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("add_remark",1,"Add remark");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("add_remark",2,"Lägg till kommentar");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("booking",1,"Booking");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("booking",2,"Bokning");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("nothing_booked",1,"No items booked");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("nothing_booked",2,"Inga föremål bokade");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("unbook",1,"Unbook");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("unbook",2,"Avboka");

-- Text for items page
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("form_error",1,"Error in the form submitted!");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("form_error",2,"Det finns ett fel i formuläret!");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("item_created",1,"Item Created");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("item_created",2,"Föremål Skapat");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("category_created",1,"Category Created");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("category_created",2,"Kategori Skapad");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("translation_added",1,"Translation added");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("translation_added",2,"Översättning tillagd");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("pic_uploaded",1,"Picture Uploaded");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("pic_uploaded",2,"Bild Uppladdad");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("pic_removed",1,"Picture Removed");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("pic_removed",2,"Bild Borttagen");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("pic_error",1,"Picture not found");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("pic_error",2,"Bilden hittades inte");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("item_removed",1,"Item Removed");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("item_removed",2,"Föremål borttaget");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("category_removed",1,"Category Removed");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("category_removed",2,"Kategori borttagen");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("add_category",1,"Add category");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("add_category",2,"Lägg till kategori");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("internal_name",1,"Internal name");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("internal_name",2,"Internt namn");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("create",1,"Create");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("create",2,"Skapa");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remove_category",1,"Remove category");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remove_category",2,"Ta bort kategori");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("edit_item",1,"Edit Item");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("edit_item",2,"Redigera Föremål");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("picture",1,"Picture");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("picture",2,"Bild");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("amount",1,"Amount");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("amount",2,"Antal");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("max_to_lend",1,"Max lending amount");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("max_to_lend",2,"Max att låna");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("max_lend_info",1,"(0 = unlimited)");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("max_lend_info",2,"(0 = obegränsat)");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("max_time",1,"Max lending time");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("max_time",2,"Maxlånetid");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("max_time_info",1,"periods (half weeks)");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("max_time_info",2,"perioder (halva veckor)");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("deposition",1,"Deposition");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("deposition",2,"Deposition");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("fee",1,"Fee");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("fee",2,"Avgift");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remove_pic",1,"Remove Picture");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remove_pic",2,"Ta Bort Bild");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remove_item",1,"Remove Item");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remove_item",2,"Ta bort föremål");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("translations",1,"Translations");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("translations",2,"Översättningar");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("edit_language",1,"Edit language");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("edit_language",2,"Redigera språk");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("name",1,"Name");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("name",2,"Namn");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("description",1,"Description");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("description",2,"Beskrivning");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("add_item_to",1,"Add new item to");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("add_item_to",2,"Lägg till ett nytt föremål till");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("translate_category",1,"Translate Category");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("translate_category",2,"Översätt Kategori");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("add_translation",1,"Add translation");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("add_translation",2,"Lägg till översättning");

-- Translations for bookings page
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("booking_removed",1,"Booking Removed");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("add_translation",2,"Bokning borttagen");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("booking_created",1,"Booking created");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("booking_created",2,"Bokning skapad");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("booking_updated",1,"Booking updated");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("booking_updated",2,"Bokning skapad");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("book_error",1,"The change could not be performed. There is not enough items free.");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("book_error",2,"Det gick inte att genomföra förändringen. Fullbokat för det valda antalet föremål av den typen för den valda tiden.");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("item",1,"Item");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("item",2,"Föremål");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("booked_by",1,"booked by");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("booked_by",2,"bokad av");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("pick_up",1,"Pick up");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("pick_up",2,"Utlämning");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("return",1,"Return");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("return",2,"Återlämning");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("back_to_booking",1,"Back to the booking");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("back_to_booking",2,"Tillbaks till bokningen");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("add_item",1,"Add item");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("add_item",2,"Lägg till föremål");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remove_booking",1,"Remove booking");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remove_booking",2,"Ta bort bokning");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("back_to_session",1,"Back to the session");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("back_to_session",2,"Tillbaks till passet");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("bookings_search",1,"Bookings search");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("bookings_search",2,"Bokningar sök");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("search_results",1,"Search results");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("search_results",2,"Sökresultat");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("no_bookings_on_search",1,"No bookings found on search");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("no_bookings_on_search",2,"Hittade inga bokningar på sökfrågan");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("bookings_desc",1,"Here all boookings are listed, old, new, late etc.");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("bookings_desc",2,"Här listas alla bokningar, gamla, nya, försenade osv.");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("new_booking",1,"New booking");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("new_booking",2,"Ny bokning");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("search",1,"Search");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("search",2,"Sök");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("pick_up_date_desc",1,"Pick up date YYYY-mm-dd");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("pick_up_date_desc",2,"Uthämtningsdatum YYYY-mm-dd");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("return_date_desc",1,"Return date YYYY-mm-dd");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("return_date_desc",2,"Återlämningsdatum YYYY-mm-dd");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("edit_user",1,"Edit user");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("edit_user",2,"Redigera användare");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remarks",1,"Remarks");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remarks",2,"Anmärkningar");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("new_remark",1,"New remark");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("new_remark",2,"Ny anmärkning");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("edit",1,"Edit");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("edit",2,"Redigera");

----------------- ADDED ----------------
