<?php
/**
 * Contains the LabelTest class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-10-20
 *
 */

namespace Konekt\Stift\Tests\Feature;

use Konekt\Stift\Models\Label;
use Konekt\Stift\Models\Project;
use Konekt\Stift\Tests\TestCase;

class LabelTest extends TestCase
{
    /** @test */
    public function labels_can_be_created()
    {
        $project = factory(Project::class)->create();

        $label = Label::create([
            'title'      => 'Hello',
            'slug'       => 'hello',
            'project_id' => $project->id,
            'color'      => 'red'
        ]);

        $this->assertEquals('Hello', $label->title);
        $this->assertEquals('hello', $label->slug);
        $this->assertEquals($project->id, $label->project_id);
        $this->assertEquals('red', $label->color);
    }

    /** @test */
    public function label_slug_gets_automatically_generated()
    {
        $project = factory(Project::class)->create();

        $label = Label::create([
            'title'      => 'János Dénes',
            'project_id' => $project->id,
            'color'      => 'red'
        ]);

        $this->assertEquals('janos-denes', $label->slug);
    }

    /** @test */
    public function the_labels_project_can_be_returned()
    {
        $project = factory(Project::class)->create([
            'name' => 'Udvari Kobzos'
        ]);

        $label = Label::create([
            'title'      => 'Orbán Déenes',
            'project_id' => $project->id,
            'color'      => 'orange'
        ]);

        $this->assertEquals($label->project->name, 'Udvari Kobzos');
        $this->assertEquals($label->project->id, $project->id);
    }

    /** @test */
    public function a_project_can_return_all_its_labels()
    {
        $project1 = factory(Project::class)->create();
        $project2 = factory(Project::class)->create();

        Label::create([
            'title'      => 'First Label',
            'project_id' => $project1->id,
            'color'      => 'orange'
        ]);

        Label::create([
            'title'      => 'Second Label',
            'project_id' => $project1->id,
            'color'      => 'green'
        ]);

        Label::create([
            'title'      => 'Third Label',
            'project_id' => $project1->id,
            'color'      => 'orange'
        ]);

        Label::create([
            'title'      => 'Blue Label',
            'project_id' => $project2->id,
            'color'      => 'blue'
        ]);

        Label::create([
            'title'      => 'Purple Label',
            'project_id' => $project2->id,
            'color'      => 'purple'
        ]);

        Label::create([
            'title'      => 'Khaki Label',
            'project_id' => $project2->id,
            'color'      => 'khaki'
        ]);

        Label::create([
            'title'      => 'Turquoise Label',
            'project_id' => $project2->id,
            'color'      => 'turquoise'
        ]);

        $this->assertCount(3, $project1->labels);
        $this->assertCount(4, $project2->labels);
    }

    /** @test */
    public function color_as_hex_returns_the_color_value_if_it_starts_with_a_hashmark()
    {
        $project = factory(Project::class)->create();

        $label = Label::create([
            'title'      => 'Turquoise Label',
            'project_id' => $project->id,
            'color'      => '#1f2d00'
        ]);

        $this->assertEquals('#1f2d00', $label->colorAsHex());
    }

    /** @test */
    public function color_as_hex_converts_the_string_to_the_appshell_themes_color()
    {
        $project = factory(Project::class)->create();

        $label = Label::create([
            'title'      => 'Turquoise Label',
            'project_id' => $project->id,
            'color'      => 'primary'
        ]);

        $this->assertEquals('#026c7c', $label->colorAsHex());
    }
}
