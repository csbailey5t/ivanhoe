<?php

require_once dirname(__FILE__) . "/BasePostForm.php";

/**
 * This defines the concrete implementation for the game form.
 */
class IvanhoeMove extends BasePostForm
{

    public function get_post_type()
    {
        return 'ivanhoe_move';
    }

    public function populate_labels()
    {
        $this->form_title    = __( 'Make a Move', 'ivanhoe' );
        $this->title_label   = __( 'Move Title', 'ivanhoe' );
        $this->content_label = __( 'Move Content', 'ivanhoe' );
        $this->other_label   = __( 'Rationale', 'ivanhoe' );
    }

    public function render_thumbnail()
    {
        return;
    }
    public function render_rationale()
    {
        echo "<div><label for='post_rationale'>$this->other_label</label>";
        $this->wp_editor($this->rationale_content, 'post_rationale', array('media_btns' => false));
        echo "</div>";
    }

    public function render_sources()
    {
        if ($this->move_source) {
            echo "<h3>You are responding to the following moves:</h3>";
            echo "<div id='all-sources'>";
            foreach ($this->move_source as $move) {
                $title = get_the_title($move);
                $content_post = get_post($move);
                $content = $content_post->post_content;
                $content = apply_filters('the_content', $content);
                $buffer = "<h4>Post title: " . $title . "</h4>";
                $buffer .=  $content;
                echo $buffer;
            }
            echo "</div>";
        }
    }

    public function get_move_source_message($game)
    {
        $buffer = sprintf(
            __( 'You are making a move on the game '
                . '&#8220;<a href="%1$s">%2$s</a>&#8221;', 'ivanhoe'),
            get_permalink($parent_post),
            $game->post_title
        );
        if ($this->move_source) {
            $buffer .= sprintf(
                __( ' in response to the following: <ul>' , 'ivanhoe' ),
                get_permalink($this->parent_post),
                $game->post_title
            );

            foreach ($this->move_source as $move) {
                $link  = get_permalink($move);
                $title = get_the_title($move);
                $buffer .= "<li><a href='$link'>$title</a></li>";
            }

            $buffer .= "</ul>";

        } else {
            $buffer .= ".";
        }

        return $buffer;
    }

}


