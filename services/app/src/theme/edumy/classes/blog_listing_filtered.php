<?php

/**
 * Extended blog listing class that filters entries by user roles and tags
 */

require_once($CFG->dirroot . '/blog/locallib.php');

class theme_edumy_blog_listing_filtered extends blog_listing {
    
    private $filteredentries = null;
    private $filteredtotalentries = null;
    
    /**
     * Get filtered entries based on user roles and tags
     */
    public function get_entries($start=0, $limit=10) {
        // Make sure we have the filtered entries cached
        $this->get_filtered_entries();
        
        // Apply pagination to filtered results
        return array_slice($this->filteredentries, $start, $limit, true);
    }

    /**
     * Count filtered entries
     */
    public function count_entries() {
        // Make sure we have the filtered entries cached
        $this->get_filtered_entries();
        
        return $this->filteredtotalentries;
    }
    
    /**
     * Get and cache all filtered entries
     */
    private function get_filtered_entries() {
        global $DB;
        
        if ($this->filteredentries === null) {
            if ($sqlarray = $this->get_entry_fetch_sql(false, 'created DESC')) {
                // Get all entries first (without pagination)
                $allentries = $DB->get_records_sql($sqlarray['sql'], $sqlarray['params']);
                
                // Apply tag filtering
                $this->filteredentries = $this->filter_entries_by_tags($allentries);
                $this->filteredtotalentries = count($this->filteredentries);
            } else {
                $this->filteredentries = array();
                $this->filteredtotalentries = 0;
            }
        }
        
        return $this->filteredentries;
    }
    
    /**
     * Filter entries based on user roles and tags
     */
    private function filter_entries_by_tags($entries) {
        global $USER;
        
        $filteredentries = array();
        
        // Get current user's roles
        $currentRoleNames = array();
        $context = context_system::instance();
        $currentRoles = get_user_roles($context, $USER->id, true);
        foreach($currentRoles as $currentRole) {
            $currentRoleNames[] = $currentRole->name;
        }
        
        foreach ($entries as $entry) {
            // Get tags for this entry
            $currentTags = core_tag_tag::get_item_tags_array('core', 'post', $entry->id);
            
            // Create blog entry object to check edit permissions
            $blogentry = new blog_entry(null, $entry);
            $blogentry->prepare_render();
            
            // Apply the same filtering logic as in the renderer
            if(!in_array('tutti',$currentTags)  // non c'è il tag tutti
                && empty(array_intersect($currentRoleNames,$currentTags))   // non c'è il tag corrispondente al ruolo
                && !$blogentry->renderable->usercanedit) {  // l'utente non può editare il post
                // Skip this entry
                continue;
            }
            
            // Include this entry
            $filteredentries[$entry->id] = $entry;
        }
        
        return $filteredentries;
    }
    
    /**
     * Clean tags from role-related tags for display purposes
     * This is the same logic as in the renderer for category display
     */
    public static function clean_display_tags($entryId, $currentRoleNames) {
        $currentTags = core_tag_tag::get_item_tags_array('core', 'post', $entryId);
        
        // Pulizia lista tag dai ruoli (tutti, Facilitatore, Volontario) ed estrazione primo tag rimanente per categoria
        // Oppure default a Senza categoria
        $blockedRole = array("Volontario", "volontario", "Facilitatore", "facilitatore", "Studente", "studente", "Utente", "utente");

        foreach ($currentRoleNames as $role) {
            if (in_array($role, $blockedRole)) {
                foreach ($currentTags as $id => $current_tag) {
                    if ($current_tag == 'tutti' || $current_tag == 'Facilitatore' || $current_tag == 'Volontario' || $current_tag == 'Studente' || $current_tag == 'Utente') {
                        unset($currentTags[$id]);
                    }
                }
            }
        }
        
        if (empty($currentTags)) {
            $currentCategory = array('Senza categoria');
        } else {
            $currentCategory = $currentTags;
        }
        
        return $currentCategory;
    }
}
